<?php
namespace GiftTiny\Plugin\Db; 

class Utils {
  public static function getOrm(): \Symlink\ORM\Manager
  {
    return \Symlink\ORM\Manager::getManager();
  }
  public static function getOrmRepo($repo): \Symlink\ORM\Repositories\BaseRepository
  {
    return (self::getOrm())->getRepository($repo);
  }
  public static function getQueryBuilder($repo): \Symlink\ORM\QueryBuilder
  {
    return (self::getOrmRepo($repo))->createQueryBuilder();
  }
}
