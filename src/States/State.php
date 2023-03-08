<?php
declare(strict_types=1);
namespace Tualo\Office\OnlineVote\States;

interface State {
    public function prepare(&$request,&$result):string; // called to prepare the next state
    public function transition(&$request,&$result):string;
}