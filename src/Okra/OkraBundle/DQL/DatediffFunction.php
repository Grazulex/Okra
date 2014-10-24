<?php
 
namespace Okra\OkraBundle\DQL;
 
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
 
/**
* "YEAR" "(" SimpleArithmeticExpression ")"
*
* @category DoctrineExtensions
* @package DoctrineExtensions\Query\Mysql
* @author Rafael Kassner <kassner@gmail.com>
* @license MIT License
*/
class DatediffFunction extends FunctionNode
{
    public $time1;
    public $time2;
 
    /**
* @override
*/
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return "DATEDIFF(" . $sqlWalker->walkArithmeticPrimary($this->time1) . ", " . $sqlWalker->walkArithmeticPrimary($this->time2). ")";
    }
 
    /**
* @override
*/
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
 
        $this->time1 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->time2 = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}