# vstr-lggr

A minimalistic visitor logging tool for tracking website visits.  
It provides a simple web interface to review visits, filter by time range, and inspect visited subpages.  
The project is intentionally lightweight and rudimentary.

---

## Features

- Track website visits in real time  
- View detailed visit data (IP, ISP, user agent, visited URL, etc.)  
- Filter visits by selected time ranges  
- web interface 
---

## Preview

![Screenshot web interface](https://raw.githubusercontent.com/vadimcx/vstr-lggr/refs/heads/main/msc/imgs/Screenshot.png)

---

## Requirements

- PHP 7+ capable server  
- MySQL / MariaDB database  

---

## Deployment

1. **Create the database table:**

   ```sql
   CREATE TABLE visits (
     id INT AUTO_INCREMENT PRIMARY KEY,
     ip VARCHAR(45) NOT NULL,
     request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
     last_request_date DATETIME DEFAULT CURRENT_TIMESTAMP,
     request_cnt INT DEFAULT 1,
     continent VARCHAR(50),
     country VARCHAR(100),
     flag VARCHAR(50),
     isp VARCHAR(255),
     agent VARCHAR(255),
     called_url VARCHAR(255)
   );

   ALTER TABLE visits 
     ADD UNIQUE KEY unique_visitor 
     (ip, continent, country, flag, isp, agent, called_url);
   ```

2. **Configure `Config.php`:**

   - `admin_pass_hash`: must contain the **already hashed** admin password  
   - `allowedReferer`: the base URL of the website where visits should be tracked  

3. **Include `logger.js` on all pages under the specified base URL:**

   ```html
   <script src="logger.js"></script>
   ```

---

## Usage

- Access the web interface at:  
  `https://www.your-url.com/panel/admin.php`  

- Login using the admin credentials specified in `config.php`  

- Automatic logout after **5 minutes** of inactivity  

---

## Other

- [Chota.css](https://github.com/jenil/chota) was used for the frontend styling.  

---
