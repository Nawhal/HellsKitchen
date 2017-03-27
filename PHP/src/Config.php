<?php


class Config
{
    
    public static function getDataBaseInfos()
    {
        return array(
            //'dbName'=> 'pgsql:host=tuxa.sme.utc;dbname=dbnf17p013'
            'dbName'=> 'mysql:host=localhost;dbname=nf17',
            'login' => '**',
            'mdp'   => '**'
        );
    }
}
