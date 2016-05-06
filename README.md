# sesrot
sesrot is [trut](https://github.com/simpart/trut) generator module.

This module generate uri routing file on php.

Generated source changes the routing target in accordance with the state of the session variables

# install
download this module

```bash
git clone https://github.com/simpart/sesrot.git
```

install this module

```bash
trut mod add path/to/module/dir
```

# sample
### create routing config file that is yaml type.

```yaml
first_access :       # group name
  session :
    state : first    # routing this urlmap if 'state' key session value is 'first'. 
  urlmap :
    /login : /path/to/login/page.html       # absolute path
    /auth/user : /path/to/rest/auth.php     
    /image/button : /path/to/ok/button.png

loggedin :            # group name 
  session :          
    state : loggedin  # routing this urlmap if 'state' key session value is 'loggedin'.
  urlmap :
    /top : /path/to/top/page.html
    /top/css : /path/to/top.css
    /top/js  : /path/to/top.js
    
__any__ :             # require this group
  urlmap :            # call this contents if request uri nothig matched. 
    null : /path/to/nothing/matched.php   # 'null' key is fixed value
```

**attention** : contents of urlmap must specify an existing file.

### select 'SesRot' module (first time only)

```bash
trut mod sel SesRot
```

### generate routing PHP source files.

```bash
trut gen path/to/routing/config/file [-o /output/dest/dir]
```

