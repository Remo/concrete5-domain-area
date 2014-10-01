domain area for concrete5.7
===========================

This package adds a new Area class you can use to embed content specific for a single domain.
It was built because a site might have multiple domains with mostly the same content with the exception of a contact form.

Usage
-----

Install the package and navigate to `System & Settings / Basics / Content Domains`. Enter all the domains you're using.
Next, open the theme you're working with and insert a code like this

```php
Loader::model('content_domain_area', 'domain_area');
$dd = new \Concrete\Package\DomainArea\Models\ContentDomainArea('Content');
$dd->display($c);
```

This will show you an area per domain in the format of: `Content (domain1)`. All areas are shown while you're editing the page but once you leave the edit mode, you'll only see the area matching the current domain. 

Todo
----

* Move DB access into model
* Add some kind of autoloading to avoid `Loader::model`