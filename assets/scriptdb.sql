CREATE TABLE payment_method (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  description VARCHAR(150) NULL,
  state BIT(1) NOT NULL DEFAULT b'1'
);

CREATE TABLE transaction_type (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  description VARCHAR(150) NULL,
  state BIT(1) NOT NULL DEFAULT b'1'
);

CREATE TABLE withdrawal (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  payment_method_id INT NOT NULL,
  transaction_type_id INT NOT NULL,
  date TIMESTAMP NOT NULL,
  amount FLOAT NOT NULL,
  description TEXT NOT NULL,
  FOREIGN KEY (payment_method_id) REFERENCES payment_method(id),
  FOREIGN KEY (transaction_type_id) REFERENCES transaction_type(id)
);

CREATE TABLE income (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  payment_method_id INT NOT NULL,
  transaction_type_id INT NOT NULL,
  date TIMESTAMP NOT NULL,
  amount FLOAT NOT NULL,
  description TEXT NOT NULL,
  FOREIGN KEY (payment_method_id) REFERENCES payment_method(id),
  FOREIGN KEY (transaction_type_id) REFERENCES transaction_type(id)
)
