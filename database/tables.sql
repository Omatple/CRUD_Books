-- Table authors
CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    country VARCHAR(80) NOT NULL
);

-- Table books
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) UNIQUE NOT NULL,
    synopsis TEXT NOT NULL,
    cover VARCHAR(150) NOT NULL,
    author_id INT,
    CONSTRAINT fk_book_author FOREIGN KEY (author_id) REFERENCES authors(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
