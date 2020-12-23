<?php


namespace App\Traits;


use App\Entity\Arrematante\Arrematante;
use Uloc\ApiBundle\Entity\Person\Address;
use Uloc\ApiBundle\Entity\Person\Contact;
use Uloc\ApiBundle\Entity\Person\ContactEmail;
use Uloc\ApiBundle\Entity\Person\ContactPhone;
use Uloc\ApiBundle\Entity\Person\PersonDocument;
use Uloc\ApiBundle\Entity\Person\TypeEmailPurpose;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Manager\PersonManager;

/**
 * Trait UserHelper
 * Need to use in Controller
 * @package App\Traits
 */
trait UserHelper
{

    /**
     * @param $apelido
     * @return User
     * @throws \Exception
     */
    public function test($apelido, $me = null, $exception = true, $email = null, $class = Arrematante::class)
    {
        /* @var User $test */
        $test = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['username' => $apelido], ['id' => 'desc']);

        // Caso não exista o usuário, verificar se não existe também na entidade Arrematante
        if (!$test) {
            $test2 = $this->getDoctrine()->getManager()->getRepository($class)->findOneBy(['apelido' => $apelido], ['id' => 'desc']);
            if ($test2) {
                $test = new User();
                $test->setUsername($test2->getApelido());
            }
        }

        if ($test) {
            if (null !== $me) {
                if ($apelido === $test->getUsername()) {
                    return; // Is me. Me can self update
                }
            }
            if ($exception) {
                throw new \Exception(serialize(['error' => 'validation', 'message' => 'Usuário "' . $apelido . '" já existe.']));
            }
        }

        if ($email !== null) {
            $test3 = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email' => $email], ['id' => 'desc']);
            if ($test3) {
                throw new \Exception(serialize(['error' => 'validation', 'message' => 'E-mail informado já está sendo utilizado por outro usuário: ' . $email]));
            }
        }

        return $test;
    }

    public function __verificarApelido($apelido) {
        $em = $this->getDoctrine()->getManager();
        // $test = $em->getRepository(User::class)->findOneBy(['username' => $apelido], ['id' => 'desc']);
        $test = $this->test($apelido, null, false);

        if (!$test) {
            return $this->createApiResponseEncodeArray(['disponivel' => true]);
        }


        return $this->createApiResponseEncodeArray([
            'disponivel' => false,
            'sugestao' => $this->sugerirApelido($test->getUsername()),
            'entity' => [
                'id' => $test->getId(),
                'apelido' => $test->getUsername()
            ]], 200);
    }

    protected function sugerirApelido($apelido)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * TODO: Verificar ajuste para uma query com expressão regular. Pode ocorrer situação onde haverá muitos usuários, criando um loop com muitas querys e prejudicando o desempenho da aplicação
         **/

        $test = false;
        $i = 1;
        while ($test == false) {
            $testObj = $em->getRepository(User::class)->findOneBy(['username' => $apelido . $i], ['id' => 'desc']);

            if (!$testObj) {
                $test = true;
                return $apelido . $i;
            }
            $i++;
        }

    }

    protected function bindPerson($entity, PersonManager $personManager, $original = null)
    {
        $personModel = $entity->getPessoa();
        if ($entity->isNew()) {
            $person = $personManager->create(
                $personModel->getName(),
                $personModel->getType(),
                true,
                [
                    'document' => preg_replace('/\D/', '$1', $personModel->getDocument())
                ]
            );
        } else {
            $person = $entity->getPessoa();
            $person->setDocument(preg_replace('/\D/', '$1', $person->getDocument()));
            $personManager->manager($person);
            $personManager->enablePersist();
        }

        $personManager->disableFlush();

        $person->setBirthDate($personModel->getBirthDate());
        $person->setGender($personModel->getGender());

        if ($personModel->getDocuments()) {
            /* @var $document PersonDocument */
            foreach ($personModel->getDocuments() as $document) {
                if ($document->isNew()) {
                    $personManager->addDocument($document->getIdentifier(), $document->getAgentDispatcher(), $document->getExpedition(), $document->getType());
                    $personModel->getDocuments()->removeElement($document);
                } else {
                    $personManager->updateDocument($document);
                }
            }
        }

        if ($personModel->getAddresses()) {
            /* @var $address Address */
            foreach ($personModel->getAddresses() as $address) {
                if ($address->isNew()) {
                    $personManager->addAddress(
                        $address->getAddress(),
                        $address->getComplement(),
                        $address->getNumber(),
                        $address->getDistrict(),
                        $address->getDistrictId(),
                        $address->getZip(),
                        $address->getCity(),
                        $address->getCityId(),
                        $address->getState(),
                        $address->getStateId(),
                        $address->getOtherPurpose(),
                        $address->getDefault(),
                        $address->getLatitude(),
                        $address->getLongitude(),
                        $address->getPurpose()
                    );
                    $personModel->getAddresses()->removeElement($address);
                } else {
                    $personManager->updateAddress($address);
                }
            }
        }

        // Ao menos 1 email deve ser enviado
        if ($personModel->getEmails()) {
            /* @var $email ContactEmail */
            foreach ($personModel->getEmails() as $email) {
                if ($email->isNew()) {
                    $personManager->addEmail(
                        $email->getEmail(),
                        $email->getValid(),
                        $email->getDefault(),
                        $email->getOtherPurpose(),
                        $email->getPurpose() ? $email->getPurpose() : $this->getDoctrine()->getManager()->getRepository(TypeEmailPurpose::class)->findOneByCode('pessoal'),
                        );
                    $personModel->getEmails()->removeElement($email);
                } else {
                    $personManager->updateEmail($email);
                }
            }
        }

        if ($personModel->getPhoneNumbers()) {
            /* @var $phone ContactPhone */
            foreach ($personModel->getPhoneNumbers() as $phone) {
                // dump($phone);
                if ($phone->isNew()) {
                    $personManager->addPhone(
                        $phone->getAreaCode(),
                        $phone->getPhoneNumber(),
                        $phone->getCellphone(),
                        $phone->getDefault(),
                        null,
                        $phone->getOtherPurpose(),
                        $phone->getPurpose(),
                        );
                    $personModel->getPhoneNumbers()->removeElement($phone);
                } else {
                    $personManager->updatePhone($phone);
                }
            }
        }

        if ($personModel->getContacts()) {
            /* @var $contact Contact */
            foreach ($personModel->getContacts() as $contact) {
                if ($contact->isNew()) {
                    $personManager->addContact(
                        $contact->getName(),
                        $contact->getTag(),
                        $contact->getValue(),
                        null,
                        $contact->getPurpose(),
                        );
                    $personModel->getContacts()->removeElement($contact);
                } else {
                    $personManager->updateContact($contact);
                }
            }
        }

        $entity->setPessoa($person);
        return $person;
    }

}