<?php

require_once './_Core/Interfaces/IDatabaseBuilder.php';

class MySQLDatabaseBuilder implements IDatabaseBuilder {

    private array $connectionSettings;
    private mysqli $connection;

    public function __construct(array $connectionSettings) {
        $this->connectionSettings = $connectionSettings;
    }

    function createConnetion(): IDatabaseBuilder {    
        $db_host = $this->connectionSettings['host'];
        $db_username = $this->connectionSettings['username'];
        $db_password = $this->connectionSettings['password'];
        $db_name = $this->connectionSettings['db_name'];
    
        $this->connection = new mysqli($db_host, $db_username, $db_password);
        $this->connection->select_db($db_name);
    
        return $this; 
    }

    public function autoCreateDatabase(): IDatabaseBuilder {
        $db_name = SETTINGS['database_con']['db_name'];
    
        if(!$this->connection->select_db($db_name)) {
            $this->connection->query("CREATE DATABASE $db_name;");
        }

        return $this;
    }
    
    public function autoCreateTables(bool $autoIncrementPK = true): IDatabaseBuilder {
        $types = array('php');
        $path = "./models";
        
        $dir = new DirectoryIterator($path);
        
        $this->connection->begin_transaction();
        foreach($dir as $archive) {
            $extension = strtolower($archive->getExtension());
            if(!in_array($extension, $types)) {
                continue;
            }
    
            $filename = explode('.', $archive->getFilename())[0];
            require_once "$path/$filename.php";
    
            $modelName = explode('Model', $filename)[0];
    
            $ref = new ReflectionClass($filename);

            // Pegar propriedades
            $properties = $ref->getProperties(ReflectionMethod::IS_PUBLIC);
            $pk = $ref->getDefaultProperties()['primaryKey'];
            $fk = $ref->getDefaultProperties()['foreignKey'];

            $propsAndTypes = array();
            foreach($properties as $prop) {
                $propAndType = array();
                $refProp = new ReflectionProperty($filename, $prop->name);

                $propAndType['name'] = $refProp->getName();
                $propAndType['type'] = $refProp->getType()->getName();

                array_push($propsAndTypes, $propAndType);
            }

            $query = "CREATE TABLE IF NOT EXISTS $modelName (";
            
            $sqlType = array(
                'int' => 'INT(10)',
                'float' => 'DECIMAL(10,2)',
                'string' => 'TEXT',
                'bool' => 'TINYINT',
                'DateTime' => 'DATETIME'
            );

            foreach($propsAndTypes as $propAndType) {
                $query .= $propAndType['name'] . ' ';
                $query .= $sqlType[$propAndType['type']] . ' ';
                $query .= "NOT NULL"; 

                if($propAndType['name'] == $pk) {
                    if($autoIncrementPK) {
                        $query .= ' AUTO_INCREMENT';
                    }
                }

                $query .= ', ';
            }

            if(!$pk) {
                throw new Exception("Chave primária não definida em $filename");
            }

            $query .= "PRIMARY KEY($pk)";
            $query .= ");";

            $this->connection->query($query);
        }

        try {
            $this->connection->commit();
        } catch (Exception $e) {
            throw new Exception("Falha ao criar tabelas!");
        }

        return $this;
    }

    public function getConnection() {
        return $this->connection;
    }

}

?>