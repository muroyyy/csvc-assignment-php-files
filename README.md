# MSRI PHP Query Web App

This is a lightweight PHP-based web application designed for querying and displaying country-level demographic and development data. It integrates with AWS Secrets Manager and RDS (MySQL) securely via AWS SDK for PHP.

---

## 🧰 Tech Stack

- PHP (Vanilla)
- MySQL (Amazon RDS)
- AWS Secrets Manager (Credential Management)
- Apache2 Web Server
- AWS SDK for PHP (via Composer)

---

## 🚀 Deployment Steps

> 📝 **Assumption**: You are deploying this on an Ubuntu EC2 instance and cloning to `/var/www/html`.

---

### 1️⃣ System Requirements

Install the necessary packages:

```bash
sudo apt update && sudo apt install -y apache2 php libapache2-mod-php php-mysql unzip git composer
```

### 2️⃣ Clone the Repository

```bash
cd /var/www/html
sudo git clone https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git .
sudo chown -R www-data:www-data /var/www/html
```

### 3️⃣ Install PHP Dependencies

Use Composer to install AWS SDK:
```bash
composer require aws/aws-sdk-php
```
✅ This will generate the vendor/ directory with autoloading capabilities.

### 4️⃣ Create Secrets in AWS Secrets Manager

Go to AWS Secrets Manager, create a secret with these key-value pairs:
| Key        | Value                                              |
| ---------- | -------------------------------------------------- |
| `username` | e.g., `appuser`                                    |
| `password` | Your RDS database password                         |
| `host`     | Your RDS endpoint (e.g. `xxxxx.rds.amazonaws.com`) |
| `dbname`   | e.g., `msri_db`                                    |

🆔 Use the same secret name defined in config.php (default: php/mysql/msri-db-credentials)

### 5️⃣ Setup MySQL Database in RDS

Ensure your Amazon RDS MySQL instance contains the following:
- A database named: msri_db
- A table: countrydata_final
- Columns: name, mobilephones, population, GDP, lifeexpectancy, mortalityunder5, etc.

Create a user (e.g. appuser) with SELECT privileges on this DB.

### 6️⃣ Verify Apache Permissions

Ensure Apache (www-data) can access your project files:
```bash
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
```

### 7️⃣ Test the Website

Launch the site:
```bash
http://<your-public-dns-or-alb>/index.php
```

---

## ✅ Useful Files

| File         | Purpose                                 |
| ------------ | --------------------------------------- |
| `index.php`  | Landing page                            |
| `query.php`  | Dropdown form to select data type       |
| `query2.php` | Dynamically fetches query results       |
| `query3.php` | Sample fallback query for demo purposes |
| `config.php` | Connects to AWS Secrets Manager and RDS |
| `style.css`  | Basic styling                           |
| `vendor/`    | AWS SDK via Composer                    |

---

## ⚠️ Security Tip

- Avoid hardcoding credentials.
- Never expose vendor/ folder publicly unless required.
- Use HTTPS in production (configure with SSL certs).
- Make sure config.php and .env (if added) are not accessible via web.
