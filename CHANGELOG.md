## Changelog

### Version 1.0.5
- **Compatibility**: Add compatibility for Magento 2.4.6-p3 and Magento 2.4.7

### Version 1.0.4
- **Enhancement**: Add url relationship functionality to be able to specify custom url request and target paths for canonical and alternate attributes [#3]

### Version 1.0.3
- **Enhancement**: Add a `Hreflang identifier` to CMS Pages in order to group pages for referencing the back links. [#2]

### Version 1.0.2
- **Enhancement**: Move the block classes to custom view model to avoid extending Magento's Template block class. [#1]

### Version 1.0.1
- **Feature**: Implement canonical link tag.
- **Fix**: Apply a fix to CMS pages, where all multi-store pages must work in pairs and return the favor with a hreflang tag pointing back to the origin of the reference.
E.g. German variant that was reference from English version must point back to English variant.

### Version 1.0.0
- **Feature**: Implement hreflang attribute.
- **Feature**: New SEO Suite module for Magento 2.
