<?php

declare(strict_types=1);

namespace App\Library\Questionnaire;

use stdClass;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use App\Models\Masters\Questionnaires;

class QuestionnaireLibrary
{
    public const MAX_CODE_TRIAL_COUNT = 5; // 認証コードの最大確認回数

    /**
     * validate user questionnaire.
     *
     * @param array $answers answers
     * @param array $questionns questionns
     * @return bool
     * @throws MyApplicationHttpException
     */
    public static function validateQuestionnaireAnswer(
        array $answers,
        array $questionns,
    ): void {
        if (empty($answers)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_422,
                '解答情報がありません。',
                ['answers' => $answers]
            );
        }

        if (empty($questionns)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_422,
                '質問情報がありません。',
                ['questionns' => $questionns]
            );
        }

        $questionns = array_column($questionns, null, Questionnaires::QUESTION_KEY_KEY);

        foreach ($answers as $answer) {
            $key = $answer[Questionnaires::QUESTION_KEY_KEY];
            $questionn = $questionns[$key] ?? null;

            if (empty($questionn)) {
                throw new MyApplicationHttpException(
                    StatusCodeMessages::STATUS_422,
                    '解答情報に紐づく質問情報がありません。',
                    [
                        'key' => $key,
                        'questionn' => $questionn,
                    ]
                );
            }

            $type = $questionn[Questionnaires::QUESTION_KEY_TYPE];

            // 選択形式
            if (in_array($type, Questionnaires::SELECT_QUESTION_TYPE_LIST, true)) {
                if (self::isSelectedChoices($answer[Questionnaires::QUESTION_KEY_CHOICES])) {
                    throw new MyApplicationHttpException(
                        StatusCodeMessages::STATUS_422,
                        '解答情報が未入力です。',
                        [
                            'key' => $key,
                            'answerChocies' => $answer[Questionnaires::QUESTION_KEY_CHOICES],
                        ]
                    );
                }
            } else {
                // 文字列入力方式
                if (!self::isInputText($answer[Questionnaires::QUESTION_KEY_TEXT])) {
                    throw new MyApplicationHttpException(
                        StatusCodeMessages::STATUS_422,
                        '解答情報が未入力です。',
                        [
                            'key' => $key,
                            'answerText' => $answer[Questionnaires::QUESTION_KEY_TEXT],
                        ]
                    );
                }

                $maxCount = $type === Questionnaires::QUESTION_TYPE_TEXT
                    ? Questionnaires::TEXT_MAX_COUNT
                    : Questionnaires::TEXT_AREA_MAX_COUNT;

                if ($type === Questionnaires::QUESTION_TYPE_TEXT) {
                    if (!self::isLesserThanMaxCountText($answer[Questionnaires::QUESTION_KEY_TEXT], $maxCount)) {
                        throw new MyApplicationHttpException(
                            StatusCodeMessages::STATUS_422,
                            '入力された文字数が超過しています。',
                            [
                                'key' => $key,
                                'answerText' => $answer[Questionnaires::QUESTION_KEY_TEXT],
                            ]
                        );
                    }
                }
            }
        }

        return;
    }

    /**
     * check is not empty selected collections.
     *
     * @param array $keys keys
     * @return bool
     */
    public static function isSelectedChoices(array $keys): bool
    {
        return !empty($keys);
    }

    /**
     * check is not empty text keys.
     *
     * @param ?string $text text
     * @return bool
     */
    public static function isInputText(?string $text): bool
    {
        return !empty($text);
    }

    /**
     * check text is lesser than max count
     *
     * @param int $text text
     * @param int $maxCount max count
     * @return bool
     */
    public static function isLesserThanMaxCountText(int $text, int $maxCount): bool
    {
        return $text <= $maxCount;
    }
}
