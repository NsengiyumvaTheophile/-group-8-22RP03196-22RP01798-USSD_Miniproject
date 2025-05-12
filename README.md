# group-8-22RP03196-22RP01798-USSD_Miniproject
# 📱 USSD OOP-Based App – TRACKLOST Ltd

This USSD application is built using PHP (OOP), PDO, and Africa’s Talking API. It allows users to register, check balance, and send money. 
All transactions are PIN-protected, and users receive SMS confirmations.




## ⚙️ Technologies Used

- PHP (OOP)
- PDO (secure database connection)
- MariaDB / MySQL
- Africa’s Talking API
- Postman (for testing)

---

## 🧩 USSD Features

1. **Register**
   - Default PIN: `1234`
   - Initial balance: `1000 RWF`
   - Welcome SMS on success

2. **Send Money**
   - Input receiver phone → amount → confirm → enter PIN
   - Fee calculated
   - SMS confirmation sent to both parties

3. **Check Balance**
   - Requires correct PIN
   - Sends SMS with current balance



5. **Navigation**
   - `0`: Go Back
   - `99`: Main Menu

---

## 🔐 PIN Security

- Transactions require valid user PIN.
- PIN is stored securely (plaintext or hashed as per your setup).

---

## 🧾 Database Setup

**Database:** `ussd_miniproject`

### Table: `users`

| Field       | Type          | Description                    |
|-------------|---------------|--------------------------------|
| id          | INT, PK       | Auto-increment ID              |
| phone       | VARCHAR(15)   | Unique user phone              |
| balance     | DECIMAL(10,2) | Initial: 1000.00               |
| created_at  | TIMESTAMP     | Auto-timestamp                 |
| pin         | VARCHAR(10)   | Default: 1234                  |

### Table: `transactions`

| Field          | Type            | Description                      |
|----------------|------------------|----------------------------------|
| id             | INT, PK          | Auto-increment                   |
| sender_phone   | VARCHAR(15)      | Sender’s number                  |
| receiver_phone | VARCHAR(15)      | Receiver’s number                |
| amount         | DECIMAL(10,2)    | Amount sent                      |
| fee            | DECIMAL(10,2)    | Transaction fee                  |
| status         | ENUM             | 'pending', 'confirmed'           |
| created_at     | TIMESTAMP        | Auto timestamp                   |

---
