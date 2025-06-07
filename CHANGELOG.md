# Changelog

## V0.0.3
Now you may declare, that spatie-roles should be checked in the controllers, when api-calls made.
config/hpm.php

if 'check_spatie_role' => true, then the role-check is made.

'needed_role' => ['super_admin', 'hpm_admin']
One of these roles is needed to be able to access the controller-method.

```bash
...
    'check_spatie_role' => true,
    'needed_role' => ['super_admin', 'hpm_admin']
    ...
```

