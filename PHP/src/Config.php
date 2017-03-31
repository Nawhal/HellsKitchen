<?php

class Config
{
    
    public static function getDataBaseInfos()
    {
        return array(
            'dbName'=> 'pgsql:host=tuxa.sme.utc;dbname=dbnf17p013',
            'login' => 'nf17p013',
            'mdp'   => '**'
        );
    }
}
