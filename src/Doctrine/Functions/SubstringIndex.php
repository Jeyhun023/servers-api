<?php

declare(strict_types=1);

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * DQL pass-through for MySQL's SUBSTRING_INDEX(str, delim, count).
 */
final class SubstringIndex extends FunctionNode
{
    private Node $stringExpr;
    private Node $delimiterExpr;
    private Node $countExpr;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->stringExpr = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->delimiterExpr = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->countExpr = $parser->ArithmeticExpression();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            'SUBSTRING_INDEX(%s, %s, %s)',
            $this->stringExpr->dispatch($sqlWalker),
            $this->delimiterExpr->dispatch($sqlWalker),
            $this->countExpr->dispatch($sqlWalker),
        );
    }
}
