var dictionary = angular.module('dictionary', []);

dictionary.controller('dictionaryCtrl', function($scope, $http){

    $scope.hiddenNotification = true;
    $scope.hiddenBeginTestBtn = true;
    $scope.hiddenHello = true;
    $scope.hiddenUsernameForm = false;
    $scope.hiddenTest = true;

    var testLanguage = this;

    $scope.testLanguage = testLanguage;

    $scope.enterName = function() {
        if (!$scope.username) {
            $scope.notificationText = 'Пожалуйста, укажите Ваше имя';
            $scope.hiddenNotification = false;
        } else {
            $scope.hiddenUsernameForm = true;
            $scope.hiddenHello = false;
            $scope.hiddenBeginTestBtn = false;
        }
    }

    $scope.beginTest = function() {
        $http.post('/app.php/begin-examination', {username: $scope.username})
            .then(function(response) {
                getQuestion();
            }, function(response){
                console.log('error', response)
            });
    }

    $scope.checkAnswer = function() {

        $scope.hiddenNotification = true;

        if (!testLanguage.question.answer) {
            return;
        }

        parameters = {
            question: testLanguage.question.text,
            answer: testLanguage.question.answer
        }

        $http.get(
            '/app.php/check-answer', {params: parameters})
            .then(function(response) {

                var data = response.data;

                if (data.complete) {
                    examinationComplete(data.score, data.wrong_count);
                } else {
                    if (data.check){
                        getQuestion();
                    } else {
                        $scope.notificationText = 'Ответ неверен. Попробуйте еще раз.';
                        $scope.hiddenNotification = false;
                    }
                }

            }, function(response){
                console.log('error', response)
            });
    }

    getQuestion = function() {
        $http.get('/app.php/get-next-question')
            .then(function(response){
                question = response.data.question;
                testLanguage.question = {
                    text: question.question,
                    variants: question.variants
                };
                $scope.hiddenNotification = true;
                $scope.hiddenBeginTestBtn = true;
                $scope.hiddenTest = false;
            }, function(response) {
                console.log('error')
            });
    }

    examinationComplete = function (score, wrongCount) {
        $scope.notificationText =
            'Тест пройден. Вы набрали ' + score + ' балла(-ов) | Количество неправильных ответов: ' + wrongCount;

        $scope.hiddenNotification = false;
        $scope.hiddenTest = true;
        $scope.hiddenBeginTestBtn = false;
    }

});

