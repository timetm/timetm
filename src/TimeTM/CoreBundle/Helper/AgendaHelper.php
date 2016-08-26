<?php
/**
 * This file is part of TimeTM
 *
 * @author André andre@at-info.ch
 */


namespace TimeTM\CoreBundle\Helper;

use Symfony\Component\Form\FormError;

/**
 * class representing a weekly calendar
 */
class AgendaHelper {

    /**
	 * Entity Manager
	 *
	 * @var EntityManager $em
	 */
	protected $em;

	/**
	 * Constructor
	 *
	 * @param EntityManager $em
	 */
    public function __construct(\Doctrine\ORM\EntityManager $em, $securityContext) {

 		$this->em = $em;
 		$this->context = $securityContext;
 	}


    /**
     * if agenda is not set to default, check if there's a default Agenda
     *
     * if not add form error
     *
     * @param $editForm
     *
     * @return $editForm
     */
    public function defaultAttributeCheck(\Symfony\Component\Form\Form $editForm) {

        $hasDefault = false;

        if ($editForm->get('default')->getData() === false) {

            $agendas = $this->context->getToken()->getUser()->getAgendas();

            foreach ($agendas as $agenda) {

                if ($agenda->getDefault()) {
                    $hasDefault = true;
                }
            }

            if ($hasDefault === false) {
                $editForm->get('default')->addError(new FormError('At least one agenda must be set as default'));
            }
        }

        return $editForm;
    }

    /**
     * if agenda set to default,remove previous default
     *
     * @param $entity
     */
    public function setDefaultAttribute(\TimeTM\CoreBundle\Entity\Agenda $entity) {

        $default = $entity->getDefault();

        if ($default) {
            $agendas = $this->context->getToken()->getUser()->getAgendas();

            foreach ($agendas as $agenda) {

                if ($agenda != $entity) {
                    $agenda->setDefault(false);
                }
            }
        }
    }
}
