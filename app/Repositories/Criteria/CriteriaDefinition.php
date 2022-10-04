<?php

namespace App\Repositories\Criteria;

class CriteriaDefinition
{
    const FIX_ID = 'id';

    const OP_LIKE = 'like';

    /**
     * Define a consulta como um where('column', 'value').
     * Nesta definição deve-se usar os parametros seguintes:
     * new CriteriaParam('column', 'value')
     */
    const SET_WHERE_EQUAL = 'where_equal';

    /**
     *
     */
    const SET_WHERE_LIKE = 'where_like';

    /**
     *
     */
    const SET_FIND_ONE = 'find_one';
    const SET_WHERE = 'where';
    const SET_OR_WHERE_LIKE = 'or_where_like';
    const SET_OR_WHERE_EQUAL = 'or_where_equal';
    const SET_OR_WHERE = 'or_where';
    const SET_ORDER_BY = 'order_by';
    const SET_GROUP_BY = 'group_by';

    /**
     * Define o criterio de filtro para buscar
     * em um campo de relacionamento
     */
    const SET_RELATIONSHIP = 'relationshiop';
    const CUSTOM_FILTER = 'custom_filter';

    const SET_WHERE_DATE = 'where_date';
    const SET_WHERE_EQUAL_DATE = 'where_equal_date';
    const SET_WHERE_BIGGER_DATE = 'where_bigger_date';
    const SET_WHERE_SMALLER_DATE = 'where_smaller_date';
    const SET_WHERE_PERIOD_DATE = 'where_period_date';
    const SET_WHERE_MONTH_YEAR = 'where_month_year';

}
