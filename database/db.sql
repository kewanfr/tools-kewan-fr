-- Table des utilisateurs
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  fullName VARCHAR(255),
  role INT DEFAULT 0,
  password VARCHAR(255) NOT NULL,
  admin BOOLEAN DEFAULT FALSE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE tools_servers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255),
  ip VARCHAR(255),
  hostname VARCHAR(255),
  description VARCHAR(255),
  icon VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tools_server_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  server_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  url VARCHAR(255) NOT NULL,
  icon VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (server_id) REFERENCES tools_servers(id) ON DELETE CASCADE
);

CREATE TABLE tools_server_apps (
  id INT AUTO_INCREMENT PRIMARY KEY,
  server_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  -- hostname VARCHAR(255),
  -- path VARCHAR(255),
  icon VARCHAR(255),
  port INT,
  protocol VARCHAR(255),
  url VARCHAR(255),
  description VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (server_id) REFERENCES tools_servers(id) ON DELETE CASCADE
);