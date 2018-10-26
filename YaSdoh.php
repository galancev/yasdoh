<?php

namespace Components;

/**
 * Класс для работы со сторожевой функцией для некорректного завершения скрипта
 * Class YaSdoh
 * @package Components
 */
class YaSdoh
{
    /**
     * Корректно ли завершён скрипт
     * @var bool
     */
    protected $isDone = false;

    /**
     * Коллбек для отработки помирания
     * @var \Closure
     */
    protected $callback;

    /**
     * YaSdoh constructor.
     */
    public function __construct()
    {
        $this->registerShutdownFunction();
    }

    /**
     * @param \Closure $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Сообщаем, что всё отработало хорошо
     */
    public function setStatusDone()
    {
        $this->isDone = true;
    }

    /**
     * Сообщаем, что всё отработало плохо
     */
    public function setStatusError()
    {
        $this->isDone = false;
    }

    /**
     * Возвращает, корректно ли завершено
     * @return bool
     */
    public function getStatus()
    {
        return $this->isDone;
    }

    /**
     * Вызывается в случае завершения скрипта
     * @return bool|mixed
     */
    protected function yaSdoh()
    {
        if ($this->getStatus())
            return true;

        if (!$this->callback)
            return false;

        return $this->callback->__invoke();
    }

    /**
     * Регистрация скрипта для завершения
     */
    protected function registerShutdownFunction()
    {
        register_shutdown_function(function () {
            $this->yaSdoh();
        });
    }
}