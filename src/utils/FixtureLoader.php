<?php
declare(strict_types=1);

namespace TaskForce\utils;

use http\Exception\RuntimeException;
use TaskForce\exceptions\SourceFileException;
use TaskForce\exceptions\FileFormatException;

class FixtureLoader
{
    private string $filename;
    private array $columns;
    private  \SplFileObject $fileObject;

    private array $result = [];

    public function __construct(string $filename, array $columns)
    {
        $this->filename = $filename;
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

        while ($line = $this->getNextLine()) {
            $this->result[] = $line;
        }

        $tableName = explode('/', explode('.', $this->filename)[0])[1];

        $filename = 'data/'. $tableName . '.sql';


        try {
            $file = new \SplFileObject($filename, 'w');
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Fail opening file for writing");
        }

        $sql = $this->combineQueryString($tableName);
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
//          Если строка в файле была пустая, то возвращаем null
            $result = $result[0] ? $result : null;
        }

        return $result;
    }

    private function validateColumns(array $columns): bool
    {
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

    private function combineQueryString(string $table):string
    {
        $columnsString = join(', ', $this->columns);

        foreach ($this->result as $record) {

            for ($i = 0; $i <= count($record) - 1; $i++) {
                $record[$i] = addslashes($record[$i]);
            }

            $row = "'" . join("', '", $record) . "'";
            $rows[] = "($row)";
        }
        $rowsString = join(', ', $rows);
        return "INSERT INTO $table ($columnsString) VALUES $rowsString;";
    }

}

