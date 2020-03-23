<?php


namespace TaskForce\utils;

use TaskForce\exceptions\SourceFileException;
use TaskForce\exceptions\FileFormatException;

class FixtureLoader
{
    private $filename;
    private $columns;
    private $fileObject;

    private $result = [];

    public function __construct(string $filename, array $columns, string $dbName)
    {
        $this->filename = $filename;
        $this->columns = $columns;
        $this->dbName = $dbName;
    }

    public function import(): void
    {
        if (!$this->validateColumns($this->columns)) {
            throw new FileFormatException("Wrong row headers");
        }

        if (!file_exists($this->filename)) {
            throw new SourceFileException("File doesn't exist");
        }

        $this->fileObject = new \SplFileObject($this->filename, "r");

        if (!$this->fileObject) {
            throw new SourceFileException("Fail opening file for reading");
        }

        $header_data = $this->getHeaderData();

        if ($header_data !== $this->columns) {
            throw new FileFormatException("Source file doesn't contain
the required rows");
        };

        while ($line = $this->getNextLine()) {
            $this->result[] = $line;
        }
    }

    public function writeSqlFlle(string $tableName): void
    {
        $filename = 'data/'. $this->dbName . '.sql';
        $file = new \SplFileObject($filename, 'w');

        if (!$file) {
            throw new SourceFileException('Fail opening file for writing');
        }

        $sql = $this->combineQueryString($tableName, $this->columns, $this->result);
        $file->fwrite($sql);
    }


    private
    function getHeaderData(): array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }

    private
    function getNextLine(): ?array
    {
        $result = null;
        if (!$this->fileObject->eof()) {
            $result = $this->fileObject->fgetcsv();
            $result = $result[0] ? $result : null;
        }

        return $result;
    }

    private
    function validateColumns(
        array $columns
    ): bool {
        $result = true;

        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    $result = false;
                }
            }
        } else {
            $result = false;
        }

        return $result;
    }

    private
    function combineQueryString(
        string $table,
        array $columns,
        array $records
    ):string {

        $columnsString = join(', ', $columns);

        foreach ($records as $record) {
            $row = "'" . join("', '", $record) . "'";
            $rows[] = "($row)";
        }
        $rowsString = join(', ', $rows);
        return "INSERT INTO $table ($columnsString) VALUES $rowsString;";
    }

}
