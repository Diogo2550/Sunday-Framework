<?php

namespace Core\Interfaces;

interface IDatabaseBuilder {

    public function createConnetion(): IDatabaseBuilder;
    public function autoCreateDatabase(): IDatabaseBuilder;
    public function autoCreateTables(bool $autoIncrementPK = true): IDatabaseBuilder;
    public function getConnection();

}