<?php

namespace Shtumi\UsefulBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Lexer;


class IfStatement extends FunctionNode
{
    // (1)

    public $f1 = null;
    public $f2 = null;
    public $f3 = null;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); // (2)

        $parser->match(Lexer::T_OPEN_PARENTHESIS); // (3)
        $this->f1 = $parser->SimpleConditionalExpression(); // (4)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->f2 = $parser->ArithmeticPrimary(); // (6)
        $parser->match(Lexer::T_COMMA); // (5)
        $this->f3 = $parser->ArithmeticPrimary(); // (6)
        $parser->match(Lexer::T_CLOSE_PARENTHESIS); // (3)
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'IF(' .
            $this->f1->dispatch($sqlWalker) . ', ' .
            $this->f2->dispatch($sqlWalker) . ', ' .
            $this->f3->dispatch($sqlWalker) .
            ')'; // (7)
    }
}