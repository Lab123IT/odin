<?php
namespace Lab123\Odin\Enums;

use Lab123\Odin\Enums\Enum;

abstract class RequestReservedWords extends Enum
{

    const fields = 'fields';

    const criteria = 'criteria';

    const includes = 'includes';

    const limit = 'limit';

    const order = 'order';

    const group = 'group';

    const page = 'page';

    const queries = 'queries';
}