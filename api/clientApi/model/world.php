<?php

class WorldW {
    private $conn;
    private $table_name = "worlds";
    
    private $id;
    private $name;
    private $idLocation;

    // konstruktor
    public function __construct($db) {
        if(get_class($db) == "PDO") $this->conn = $db;
    }

    // id
    public function getId(): ?int {
        return $this->id;
    }
    public function setId(int $id): self {
        if (is_numeric($id)) {
            $this->id = $id;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }

    // Name
    public function getName(): ?string {
        return $this->name;
    }
    public function setName(string $name): self {
        if (empty($name)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Wprowadź name$name!")));
        } else if (is_numeric($name)) {
            http_response_code(400);
            exit(json_encode(array("message" => "Name nie może być liczbą!")));
        } else if (strlen($name) < 4 || strlen($name) > 64) {
            http_response_code(400);
            exit(json_encode(array("message" => "Name musi mieć od 4 do 32 znaków!")));
        } else {
            $this->name = $name;
            return $this;
        }
    }

    // idLocation
    public function getIdLocation(): ?int {
        return $this->idLocation;
    }
    public function setIdLocation(int $idLocation): self {
        if (is_numeric($idLocation)) {
            $this->idLocation = $idLocation;
            return $this;
        } else {
            http_response_code(400);
            exit(json_encode(array("message" => "Nieprawidlowy format danych")));
        }
    }

    public function create() {
        $query = "INSERT INTO {$this->table_name}
                SET name = :name,
                    idLocation = :idLocation";
        $stmt = $this->conn->preapre($query);
        $this->name = htmlspecialchars(strip_tags($this->idDevice));
        $this->idLocation = htmlspecialchars(strip_tags($this->idLocation));
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':idLocation', $this->idLocation);
        if($stmt->execute()) {
            return true;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readByName() {
        $query = "SELECT * FROM {$this->table_name} WHERE name = :name";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $stmt->bindParam(':name', $this->name);

        if($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readAll() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);

        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }

    public function readAllByLocation() {
        $query = "SELECT * FROM {$this->table_name}
                WHERE idLocation = :idLocation";
        $stmt = $this->conn->preapre($query);
        
        $this->idLocation = htmlspecialchars(strip_tags($this->idLocation));
        $stmt->bindParam(':idLocation', $this->idLocation);

        if($stmt->execute()) {
            return $stmt;
        } else {
            http_response_code(503);
            exit(json_encode(array("message" => "Blad bazy danych")));
        }
    }
}