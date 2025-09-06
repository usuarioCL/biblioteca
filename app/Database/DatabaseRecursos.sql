-- 1. Crear la base de datos
CREATE DATABASE biblioteca;
USE biblioteca;
-- 2. Crear tabla CATEGORIAS
CREATE TABLE categorias (
    idcategoria INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(100) NOT NULL
);

-- 3. Insertar datos en CATEGORIAS
INSERT INTO categorias (categoria) VALUES
('Matemáticas'),
('Comunicación'),
('Computación');

-- 4. Crear tabla SUBCATEGORIAS
CREATE TABLE subcategorias (
    idsubcategoria INT AUTO_INCREMENT PRIMARY KEY,
    subcategoria VARCHAR(100) NOT NULL,
    idcategoria INT NOT NULL,
    FOREIGN KEY (idcategoria) REFERENCES categorias(idcategoria)
);

-- 5. Insertar datos en SUBCATEGORIAS
INSERT INTO subcategorias (subcategoria, idcategoria) VALUES
('Razonamiento Lógico Matemático', 1),
('Álgebra', 1),
('Trigonometría', 1),
('Razonamiento verbal', 2),
('Composición', 2),
('Redacción', 2),
('Base de datos', 3),
('Sistemas operativos', 3),
('Lenguajes de programación', 3);

-- 6. Crear tabla EDITORIALES
CREATE TABLE editoriales (
    ideditorial INT AUTO_INCREMENT PRIMARY KEY,
    editorial VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL
);

-- 7. Insertar ejemplo de datos en EDITORIALES
INSERT INTO editoriales (editorial, nacionalidad) VALUES
('Santillana', 'España'),
('Pearson', 'Estados Unidos'),
('Norma', 'Colombia');

-- 8. Crear tabla RECURSOS
CREATE TABLE recursos (
    idrecurso INT AUTO_INCREMENT PRIMARY KEY,
    idsubcategoria INT NOT NULL,
    ideditorial INT NOT NULL,
    tipo ENUM('Físico','DIGITAL') NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    apublicacion YEAR NOT NULL,
    isbn VARCHAR(20),
    numpaginas INT,
    rutaportada VARCHAR(255),
    rutarecurso VARCHAR(255),
    estado ENUM('Bueno','Regular','Malo') NOT NULL,
    creado DATETIME DEFAULT CURRENT_TIMESTAMP,
    modificado DATETIME DEFAULT NULL,
    FOREIGN KEY (idsubcategoria) REFERENCES subcategorias(idsubcategoria),
    FOREIGN KEY (ideditorial) REFERENCES editoriales(ideditorial)
);

-- 9. Insertar ejemplo de RECURSOS (opcional)
INSERT INTO recursos (
    idsubcategoria, ideditorial, tipo, titulo, apublicacion, isbn, numpaginas, rutaportada, rutarecurso, estado
) VALUES
(1, 1, 'Físico', 'Matemáticas para todos', 2007, '978-1234567890', 250, 'portadas/matematicas_todos.jpg', NULL, 'Bueno'),
(4, 2, 'DIGITAL', 'Comunicación Efectiva', 2015, '978-0987654321', 180, 'portadas/comunicacion_efectiva.jpg', 'recursos/comunicacion_efectiva.pdf', 'Regular');