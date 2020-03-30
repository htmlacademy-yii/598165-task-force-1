<?php
declare(strict_types=1);

namespace TaskForce\utils;

use http\Exception\RuntimeException;
use TaskForce\exceptions\SourceFileException;
use TaskForce\exceptions\FileFormatException;

class FixtureLoader
{
    private string $filename;
    private string $tableName;
    private array $columns;
    private  \SplFileObject $fileObject;

    private array $result = [];

    public function __construct(string $filename, string $tableName, array $columns)
    {
        $this->filename = $filename;
        $this->tableName = $tableName;
        $this->columns = $columns;

    }

    public function import(): void
    {
        if (!$this->validateColumns($this->columns)) {
            throw new FileFormatException("Wrong row headers");
        }

        if (!file_exists($this->filename)) {
            throw new SourceFileException("File doesn't exist");
        }

        try {
            $this->fileObject = new \SplFileObject($this->filename, "r");
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Fail opening file for reading");
        }

        $headerData = $this->getHeaderData();

        if ($headerData !== $this->columns) {
            throw new FileFormatException("Source file doesn't contain required rows");
        };

        try {
            while ($line = $this->getNextLine()) {
                $this->result[] = $line;
            }
        } catch (SourceFileException $e) {
            echo ("Fail to process csv file: " . $e->getMessage());
        }


        $filename = $this->filename . '.sql';


        try {
            $file = new \SplFileObject($filename, 'w');
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Fail opening file for writing");
        }

        $sql = $this->combineQueryString($this->tableName);
        $file->fwrite($sql);
    }


    private function getHeaderData(): array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }


    private function getNextLine(): ?array
    {
        $result = null;
        if (!$this->fileObject->eof()) {
            $result = $this->fileObject->fgetcsv();
            if (!$result) {
                throw new SourceFileException("Fail to read line");
            }
            $result = $result[0] ? $result : null;
        }

        return $result;
    }

    private function validateColumns(array $columns): bool
    {

        if (count($columns)) {
            foreach ($columns as $column) {
                if (!is_string($column)) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    private function combineQueryString(string $table):string
    {
        $columnsString = implode(', ', $this->columns);

        foreach ($this->result as $record) {

            $recordLastIndex = count($record) - 1;

            for ($i = 0; $i <= $recordLastIndex; $i++) {
                $record[$i] = addslashes($record[$i]);
            }

            $row = "'" . implode("', '", $record) . "'";
            $rows[] = "($row)";
        }
        $rowsString = implode(', ', $rows);
        return "INSERT INTO $table ($columnsString) VALUES $rowsString;";
    }

}

