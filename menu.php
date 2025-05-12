<?php
require_once 'utils.php';  // Ensure this is included for the database connection

class Menu {
    public function handleRequest($sessionId, $serviceCode, $phoneNumber, $text, $parts, $level, $sms) {
        global $conn;  // Reference the database connection

        if (!$conn) {
            echo "END Database connection failed.";
            return;
        }

        // Main Menu
        if ($text == "") {
            echo "CON Welcome to USSD Service\n1. Register\n2. Send Money\n3. Check Balance\n99. Exit";
        }

        // Register
        elseif ($parts[0] == "1") {
            $this->registerUser($phoneNumber, $sms);
        }

        // Send Money
        elseif ($parts[0] == "2") {
            $this->sendMoney($phoneNumber, $parts, $level, $sms);
        }

        // Check Balance
        elseif ($parts[0] == "3") {
            $this->checkBalance($phoneNumber, $parts, $level, $sms);
        }

        // Exit
        elseif ($parts[0] == "99") {
            echo "END Goodbye!";
        } else {
            echo "END Invalid option.";
        }
    }

    private function registerUser($phoneNumber, $sms) {
        global $conn;

        $check = $conn->prepare("SELECT * FROM users WHERE phone = ?");
        $check->bind_param('s', $phoneNumber);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "END You are already registered.";
        } else {
            $defaultPin = '1234';
            $insert = $conn->prepare("INSERT INTO users (phone, balance, pin) VALUES (?, 1000, ?)");
            $insert->bind_param('ss', $phoneNumber, $defaultPin);
            $insert->execute();

            // Send SMS to user
            $message = "Welcome! You are registered with a balance of 1000 RWF. Your PIN is $defaultPin.";
            $sms->sendSMS($message, $phoneNumber);

            echo "END Registration successful! Balance: 1000 RWF. Default PIN: 1234";
        }
    }

    private function sendMoney($phoneNumber, $parts, $level, $sms) {
        global $conn;

        switch ($level) {
            case 1:
                echo "CON Enter receiver phone number:";
                break;

            case 2:
                echo "CON Enter amount to send:";
                break;

            case 3:
                $receiver = $parts[1];
                $amount = floatval($parts[2]);
                $fee = ($amount <= 500) ? 10 : (($amount <= 2000) ? 20 : 50);
                $total = $amount + $fee;
                echo "CON Send $amount RWF to $receiver\nFee: $fee RWF\nTotal: $total RWF\n1. Confirm\n2. Cancel\n0. Back\n99. Menu";
                break;

            case 4:
                $receiver = $parts[1];
                $amount = floatval($parts[2]);
                $fee = ($amount <= 500) ? 10 : (($amount <= 2000) ? 20 : 50);
                $total = $amount + $fee;

                if ($parts[3] == "1") {
                    $this->processTransaction($phoneNumber, $receiver, $amount, $fee, $total, $sms);
                } elseif ($parts[3] == "2") {
                    echo "END Transaction cancelled.";
                } elseif ($parts[3] == "0") {
                    echo "CON Enter amount to send:";
                } elseif ($parts[3] == "99") {
                    echo "CON Welcome to USSD Service\n1. Register\n2. Send Money\n3. Check Balance\n99. Exit";
                } else {
                    echo "END Invalid option.";
                }
                break;

            default:
                echo "END Invalid request.";
        }
    }

    private function processTransaction($phoneNumber, $receiver, $amount, $fee, $total, $sms) {
        global $conn;

        // Check sender balance
        $senderData = $conn->prepare("SELECT balance FROM users WHERE phone = ?");
        $senderData->bind_param('s', $phoneNumber);
        $senderData->execute();
        $senderResult = $senderData->get_result();
        if ($senderResult->num_rows == 0) {
            echo "END You are not registered.";
            return;
        }

        $row = $senderResult->fetch_assoc();
        if ($row['balance'] < $total) {
            echo "END Insufficient balance.";
            return;
        }

        // Deduct from sender
        $updateSender = $conn->prepare("UPDATE users SET balance = balance - ? WHERE phone = ?");
        $updateSender->bind_param('ds', $total, $phoneNumber);
        $updateSender->execute();

        // Check and insert receiver if not exists
        $checkReceiver = $conn->prepare("SELECT * FROM users WHERE phone = ?");
        $checkReceiver->bind_param('s', $receiver);
        $checkReceiver->execute();
        $receiverResult = $checkReceiver->get_result();

        if ($receiverResult->num_rows == 0) {
            $insertReceiver = $conn->prepare("INSERT INTO users (phone) VALUES (?)");
            $insertReceiver->bind_param('s', $receiver);
            $insertReceiver->execute();
        }

        // Add balance to receiver
        $updateReceiver = $conn->prepare("UPDATE users SET balance = balance + ? WHERE phone = ?");
        $updateReceiver->bind_param('ds', $amount, $receiver);
        $updateReceiver->execute();

        // Log transaction
        $insertTransaction = $conn->prepare("INSERT INTO transactions (sender_phone, receiver_phone, amount, fee, status) VALUES (?, ?, ?, ?, 'confirmed')");
        $insertTransaction->bind_param('ssdd', $phoneNumber, $receiver, $amount, $fee);
        $insertTransaction->execute();

        // Send confirmation SMS to both sender and receiver
        $messageSender = "Transaction successful! You sent $amount RWF to $receiver. Fee: $fee RWF.";
        $messageReceiver = "You received $amount RWF from $phoneNumber.";

        $sms->sendSMS($messageSender, $phoneNumber);
        $sms->sendSMS($messageReceiver, $receiver);

        echo "END Transaction successful!";
    }

    private function checkBalance($phoneNumber, $parts, $level, $sms) {
        global $conn;

        if (!$conn) {
            echo "END Database connection failed.";
            return;
        }

        switch ($level) {
            case 1:
                echo "CON Enter your PIN to check balance:";
                break;

            case 2:
                $inputPin = $parts[1];
                $check = $conn->prepare("SELECT balance FROM users WHERE phone = ? AND pin = ?");
                $check->bind_param('ss', $phoneNumber, $inputPin);
                $check->execute();
                $checkResult = $check->get_result();
                
                if ($checkResult->num_rows == 0) {
                    echo "END Invalid PIN or not registered.";
                } else {
                    $row = $checkResult->fetch_assoc();
                    $balance = $row['balance'];
                    $message = "Your balance is $balance RWF.";
                    $sms->sendSMS($message, $phoneNumber);
                    echo "END Your balance has been sent to you via SMS.";
                }
                break;

            default:
                echo "END Invalid request.";
        }
    }
}
?>
