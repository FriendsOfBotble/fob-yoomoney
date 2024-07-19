<?php

namespace FriendsOfBotble\Yoomoney\Libraries\Client;

use FriendsOfBotble\Yoomoney\Libraries\Common\ResponseObject;
use Psr\Log\LoggerInterface;

/**
 * Interface ApiClientInterface
 *
 * @package YooKassa
 */
interface ApiClientInterface
{
    /**
     * Создает CURL запрос, получает и возвращает обработанный ответ
     *
     * @param string $path URL запроса
     * @param string $method HTTP метод
     * @param array $queryParams Массив GET параметров запроса
     * @param string|null $httpBody Тело запроса
     * @param array $headers Массив заголовков запроса
     *
     * @return ResponseObject
     */
    public function call($path, $method, $queryParams, $httpBody = null, $headers = []);

    /**
     * Устанавливает объект для логирования
     *
     * @param LoggerInterface|null $logger Объект для логирования
     */
    public function setLogger($logger);

    /**
     * Возвращает UserAgent
     *
     * @return UserAgent
     */
    public function getUserAgent();

    /**
     * Устанавливает shopId магазина
     *
     * @param string|int $shopId shopId магазина
     * @return mixed
     */
    public function setShopId($shopId);

    /**
     * Устанавливает секретный ключ магазина
     *
     * @param string $shopPassword
     * @return mixed
     */
    public function setShopPassword($shopPassword);

    /**
     * Устанавливает OAuth-токен магазина
     *
     * @param string $bearerToken
     * @return mixed
     */
    public function setBearerToken($bearerToken);

    /**
     * Устанавливает настройки
     *
     * @param array $config
     */
    public function setConfig($config);
}
