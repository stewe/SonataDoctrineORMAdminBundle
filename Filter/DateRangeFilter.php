<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\DoctrineORMAdminBundle\Filter;

use Sonata\AdminBundle\Form\Type\Filter\DateRangeType;

class DateRangeFilter extends Filter
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @param string $field
     * @param string $data
     * @return
     */
    public function filter($queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data) || !array_key_exists('valueb', $data))
        {
            return;
        }
        
        if(!array_key_exists('year', $data['value']) || !array_key_exists('month', $data['value']) || !array_key_exists('day', $data['value'])
                || !array_key_exists('year', $data['valueb']) || !array_key_exists('month', $data['valueb']) || !array_key_exists('day', $data['valueb']))
        {
            return;
        }

        $start = $data['value']['year'].'-'.$data['value']['month'].'-'.$data['value']['day'];
        $end = $data['valueb']['year'].'-'.$data['valueb']['month'].'-'.$data['valueb']['day'];
        
        $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, '>=', $this->getName().'_start'));
        $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, '<=', $this->getName().'_end'));
        $queryBuilder->setParameter($this->getName().'_start',  $start);
        $queryBuilder->setParameter($this->getName().'_end',  $end);
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return array();
    }

    public function getRenderSettings()
    {
        return array('sonata_type_filter_daterange', array(
            'field_type'    => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel()
        ));
    }
}