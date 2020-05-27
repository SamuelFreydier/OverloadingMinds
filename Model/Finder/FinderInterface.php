<?php

namespace Model\Finder;

interface FinderInterface 
{
    public function findAll();
    public function findOneById($id);
}