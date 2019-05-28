<?php
namespace App\Validators;

use App\Traits\Singleton;

/**
 * 验证器
 */
class Validator
{
    use Singleton;

    const RULE_SEPARATER    = '|';

    const RULE_BOUNDARY     = ':';

    const PARAM_SEPARATER   = ',';

    public static function assertArray(array $mapRules, array $mapValues)
    {
        foreach ($mapRules as $item => $ruleExpression) {

            $value  = isset($mapValues[$item])  ? $mapValues[$item] : NotExists::getInstance();

            try {
                self::assertRule($ruleExpression, $value);
            } catch (\Exception $e) {
                throw new \UnexpectedValueException($item . ':' . $e->getMessage());
            }
        }
    }

    public static function assertRule(string $ruleExpression, $value)
    {
        self::getInstance()->executeRule($ruleExpression, $value);
    }

    public function executeRule (string $ruleExpression, $value)
    {
        $ruleStructure  = $this->parseRule($ruleExpression);
        array_map(function ($ruleInfo) use ($value) {
            extract($ruleInfo);
            $ruleInstance   = $this->getRule($ruleName);
            $ruleInstance->handler($value, $ruleParams);
        }, $ruleStructure);
    }

    public function parseRule(string $ruleExpression): array
    {
        $structure  = [];

        foreach (explode(self::RULE_SEPARATER, $ruleExpression) as $ruleClip) {

            $ruleResolve    = explode(self::RULE_BOUNDARY, $ruleClip);
            $ruleParams     = isset($ruleResolve[1])
                            ? explode(self::PARAM_SEPARATER, $ruleResolve[1])
                            : [];
            $structure[]    = [
                'ruleName'      => $ruleResolve[0],
                'ruleParams'    => $ruleParams,
            ];
        }

        return      $structure;
    }

    protected   function getRule (string $ruleName)
    {
        $ruleClass  = 'App\\Validators\\Rules\\' . ucfirst($ruleName);
        $callback   = [$ruleClass, 'getInstance'];

        if (is_callable($callback)) {

            return  call_user_func($callback);
        }

        throw new \Exception('Rule ' . $ruleName . ' not exists.');
    }
}
