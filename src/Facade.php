<?php

namespace Surge\LaravelSalesforce;

class Facade {
  /**
   * {@inheritDoc}
   */
   protected static function getFacadeAccessor()
   {
      return 'salesforce';
   }
}
