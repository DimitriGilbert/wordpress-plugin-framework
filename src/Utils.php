<?php
namespace Dbuild\WpPlugin;

class Utils
{
  /**
   * Running doing_filter/action on a list, returns the first running.
   *
   * @param array $haystack
   * @param string $type
   * @return string|null
   */
  public static function DoingWhichHook(array $haystack, string $type) {
    $fn = "doing_".$type;
    foreach (array_reverse($haystack) as $needle) {
      if (call_user_func_array($fn, [$needle])) {
        return $needle;
      }
    }
    return null;
  }

  /**
   * Running doing_filter on a list, returns the first running.
   *
   * @param array $haystack
   * @return string|null
   */
  public static function DoingWhichFilter(array $haystack) {
    return self::DoingWhichHook($haystack, 'filter');
  }

  /**
   * Running doing_action on a list, returns the first running.
   *
   * @param array $haystack
   * @return string|null
   */
  public static function DoingWhichAction(array $haystack) {
    return self::DoingWhichHook($haystack, 'action');
  }
}
