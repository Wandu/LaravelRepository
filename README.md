Laravel Repository
===

[![Latest Stable Version](https://poser.pugx.org/wandu/laravel-repository/v/stable.svg)](https://packagist.org/packages/wandu/laravel-repository)
[![Latest Unstable Version](https://poser.pugx.org/wandu/laravel-repository/v/unstable.svg)](https://packagist.org/packages/wandu/laravel-repository)
[![Total Downloads](https://poser.pugx.org/wandu/laravel-repository/downloads.svg)](https://packagist.org/packages/wandu/laravel-repository)
[![License](https://poser.pugx.org/wandu/laravel-repository/license.svg)](https://packagist.org/packages/wandu/laravel-repository)

[![Build Status](https://img.shields.io/travis/Wandu/LaravelRepository/master.svg)](https://travis-ci.org/Wandu/LaravelRepository)
[![Code Coverage](https://scrutinizer-ci.com/g/Wandu/LaravelRepository/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Wandu/LaravelRepository/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Wandu/LaravelRepository/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Wandu/LaravelRepository/?branch=master)

For Laravel Repository Pattern.

라라벨 모델을 기반으로 저장소 패턴을 만들기위한 패키지입니다. 모델과 컨트롤러 사이에 레이어를 두게되면 관찰자 패턴을 구현하기
편하고, 동일한 질의가 두번 요청 되었을 때, 중복으로 데이터베이스에 요청하는 것을 막을 수 있습니다.

## 사용법

3가지 방식의 저장소를 사용할 수 있습니다.

1. default repository
1. pagination repository
1. more items repository

### 1. Default Repository

```php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\ArticleHit;

class ArticleHitRepository extends Repository
{
    /** @var string */
    protected $model = ArticleHit::class;
}
```

**Methods(매서드)**




### 2. Pagination Repository

**1페이지, 2페이지, ...** 의 구조로 구현할 때 사용하는 방식입니다

```php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Wandu\Laravel\Repository\PaginationRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\User;
use Wandu\Laravel\Repository\Traits\UsePaginationRepository;

class UserRepository extends Repository implements PaginationRepositoryInterface
{
    use UsePaginationRepository;

    /** @var string */
    protected $model = User::class;
}
```

### 3. More Items Repository

**아이템 더 보기...** 의 구조로 구현할 때 사용하는 방식입니다

```php
namespace Wandu\Laravel\Repository\Stubs\Repository;

use Wandu\Laravel\Repository\MoreItemsRepositoryInterface;
use Wandu\Laravel\Repository\Repository;
use Wandu\Laravel\Repository\Stubs\Model\User;
use Wandu\Laravel\Repository\Traits\UseMoreItemsRepository;

class UserRepository extends Repository implements MoreItemsRepositoryInterface
{
    use UseMoreItemsRepository;

    /** @var string */
    protected $model = User::class;
}
```
