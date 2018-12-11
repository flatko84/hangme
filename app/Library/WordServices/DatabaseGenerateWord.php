<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\WordServices;

use App\Library\ServiceGenerateWord;

use App\Word;


/**
 * Description of DatabaseGenerateWord
 *
 * @author vdonkov
 */
class DatabaseGenerateWord implements ServiceGenerateWord {

    
    public function getWord(){
        $newword = Word::inRandomOrder()->first();
        return $newword;
        
        
        
    }
}
