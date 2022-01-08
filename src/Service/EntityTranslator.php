<?php

namespace App\Service;

class EntityTranslator
{

    /** @var string[] */
    private array $allLocales;

    public function __construct()
    {
        $this->allLocales = ['Cs', 'En', 'De'];
    }

    public function map($form, $entity, $varsLang, $vars = null)
    {
        foreach ($varsLang as $var) {
            $row = [];

            foreach ($this->allLocales as $locale) {
                $row[$locale] = $form->get("{$var}{$locale}")->getData();
            }

            $setter = "set" . ucfirst($var);
            $entity->$setter(json_encode($row));
        }

        if (!is_null($vars)) {
            foreach ($vars as $var) {
                $row = $form->get("{$var}")->getData();

                $setter = "set" . ucfirst($var);
                $entity->$setter($row);
            }
        }

        return $entity;
    }

    public function unmap($entity, $varsLang, $vars = null)
    {
        $data = [];

        foreach ($varsLang as $var) {
            $getter = "get" . ucfirst($var);
            $data[$var] = json_decode($entity->$getter());

            foreach ($data[$var] as $key => $value) {
                $data["{$var}{$key}"] = $value;
            }

            unset($data[$var]);
        }

        if (!is_null($vars)) {
            foreach ($vars as $var) {
                $getter = "get" . ucfirst($var);
                $data[$var] = $entity->$getter();
            }
        }

        return $data;
    }

}