<?php
/**
 * Консольное приложение для работы с Yandex Realty
 * Пример запуска:
 * php protected/yiic.php realty import --path='/vagrant/protected/data/realty/test.xml'
 * @link http://www.yiiframework.com/doc/guide/1.1/ru/topics.console
 */

class RealtyCommand extends CConsoleCommand {

    /**
     * @var int Счетчик ошибок импорта
     */
    private $errorCnt = 0;

    /**
     * Действие, выполняемое при запуске приложения
     * @param $path string путь к файлу XML
     * @return int Код ошибки (0 - успешно). Соответствует кол-ву неимпортированных предложений.
     */
    public function actionImport($path)
    {

        $reader = new XMLReader();
        $reader->open($path);

        # пропускаем всё до первого offer
        while ($reader->read() && $reader->name !== 'offer');

        # читаем предложения
        while ($reader->name === 'offer')
        {

            # объект SimpleXMLElement соответствующий одному предложению
            $node = new SimpleXMLElement($reader->readOuterXml());

            # внутренний id предложения
            $offerId = (int) $reader->getAttribute('internal-id');

            /**
             * Работа с предложением
             */
            # ищем существующее предложение
            if($offerId > 0){
                $realtyModel = Realty::model()->findByPk($offerId);
            }
            # если не нашли или $offerId <= 0, создаем новую модель
            if(!isset($realtyModel) || !$realtyModel)
                $realtyModel = new Realty();

            # заполняем свойства модели
            $realtyModel->setXmlAttributes($node);

            /**
             * Работа с местоположением
             */
            # если создавали новую запись о предложении,
            # то и запись о местоположении тоже новая
            if($realtyModel->isNewRecord){
                $locationModel = new Location('import');
            }
            # в противном случае должна быть запись
            else{
                $locationModel = Location::model()->findByAttributes([
                    'realty_id' => $realtyModel->id
                ]);
            }

            # заполняем данные из XML
            $locationModel->setXMLAttributes($node->location);


            /**
             * Работа с метро
             */
            # метро (связь 1 к 1 к location)
            # сведения о метро не обязательные
            if($node->location->metro && $node->location->metro->name->__toString() !== ''){
                if($locationModel->isNewRecord){
                    # если запись об адресе была новая, то и о метро запись тоже новая
                    $metroModel = new Metro('import');
                }
                else{
                    # в противном случае попробуем найти существующую запись
                    $metroModel = Metro::model()->findByAttributes([
                        'location_id' => $locationModel->id
                    ]);
                    # если не нашли существующую запись, создаем новую
                    if($metroModel === null)
                        $metroModel = new Metro();
                }

                $metroModel->setXmlAttributes($node->location->metro);
            }

            /**
             * Работа с агентом
             */
            if($realtyModel->isNewRecord){
                $agentModel = new SalesAgent('import');
            }
            else{
                $agentModel = SalesAgent::model()->findByAttributes([
                    'realty_id' => $realtyModel->id
                ]);
            }
            $agentModel->setXmlAttributes($node->{'sales-agent'});



            /**
             * Валидация данных
             */
            # если проиошли какие-то ошибки при валидации
            if(
                !$realtyModel->validate() ||
                !$locationModel->validate() ||
                (isset($metroModel) && !$metroModel->validate()) ||
                !$agentModel->validate()
            ){

                $this->errorCnt++;

                echo 'Не удалось сохранить предложение ' . $offerId . PHP_EOL;

                # выводим ошибки валидации предложения, если они есть
                if($realtyModel->hasErrors()){
                    echo 'Ошибки модели предложения:' . PHP_EOL;
                    print_r($realtyModel->getErrors());
                }


                # выводим ошибки валидации местоположения
                if($locationModel->hasErrors()){
                    echo 'Ошибки модели местоположения:' . PHP_EOL;
                    print_r($locationModel->getErrors());
                }

                # выводим ошибки валидации метро
                if(isset($metroModel) && $metroModel->hasErrors()){
                    echo 'Ошибки модели метро:' . PHP_EOL;
                    print_r($metroModel->getErrors());
                }

                # ошибки валидации агента
                if($agentModel->hasErrors()){
                    echo 'Ошибки модели агента' . PHP_EOL;
                    print_r($agentModel->getErrors());
                }

            }
            # сохраняем данные
            else{
                $realtyModel->save(false);

                $locationModel->realty_id = $realtyModel->id;
                $locationModel->save(false);

                if(isset($metroModel)){
                    $metroModel->location_id = $locationModel->id;
                    $metroModel->save(false);
                }

                $agentModel->realty_id = $realtyModel->id;
                $agentModel->save(false);

                echo 'Предложение '.$offerId.' импортировано' . PHP_EOL;

            }

            $reader->next('offer');
        }



        # код возарата (0 - успешно, остальное - код ошибки)
        return $this->errorCnt;
    }

}