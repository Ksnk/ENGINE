<?php
/**
 * ���������� ��������� cache ��� �������� � xcached
 * User: ������
 * Date: 10.06.13
 * Time: 10:52
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_nocache {
    /*<% POINT::start('ENGINE_body'); %>*/
    /**
     *  ������ � xcached
     * @static
     * @param $key
     * @param null $value
     * @param int $time � �������� 28800 - 8 �����
     * @return bool|null
     */
    static function cache($key, $value = null, $time = 28800)
    {
        return false;
    }
    /*<% POINT::finish(); %>*/
}