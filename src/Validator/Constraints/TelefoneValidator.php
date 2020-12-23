<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TelefoneValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {

        if (!$this->verificaTelefone($protocol->getDdd(), $protocol->getTelefone())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $protocol->getDdd() . $protocol->getTelefone())
                ->addViolation();
        }
    }

    protected function verificaTelefone($ddd, $telefone)
    {
        $ddd = preg_replace('/[^0-9]/', '', $ddd);
        if (strlen($ddd) < 2)
            return false;

        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        if (strlen($telefone) < 8)
            return false;

        return true;
    }
}