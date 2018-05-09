To add to your VVV, add the site as follows to `vvv-custom.yml`:

```
  pimpmylog:
    hosts:
      - pimpmylog.test
```

To test changes or reprovision just this site:

```
vagrant provision --provision-with="site-pimpmylog"
```