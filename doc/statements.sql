--CREATE 
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
ALTER TABLE visits ADD UNIQUE KEY unique_visitor (ip, continent, country, flag, isp, agent, called_url);

--INSERT 
INSERT INTO visits (ip, continent, country, flag, isp, agent, called_url)
VALUES ('192.168.1.1', 'Europe', 'Germany', 'U+1F1E9 U+1F1EA', 'Deutsche Telekom AG', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'www.test.de/xxx');
ON DUPLICATE KEY UPDATE
    last_request_date = CURRENT_TIMESTAMP,
    request_cnt = request_cnt + 1;

--SELECT BY DATE 
SELECT *
FROM visits
WHERE DATE(request_date) BETWEEN '2025-08-01' AND '2025-08-06'
   OR DATE(last_request_date) BETWEEN '2025-08-01' AND '2025-08-06';
