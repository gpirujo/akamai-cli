# Akamai simple CLI

This is a script that calls the API with the parameters you pass it in the command line. The goal is to be able to make calls quickly without too much preparation, or to integrate them in scripts.

Akamai has their own CLI, but it's hard to integrate with other tools.

All the API responses are JSON objects and are pretty-printed to stdout. You can pipe them into (for example) `jq` to extract the individual data.

## Preparation

Get API credentials and save them in a file.

```
$ cat >credentials.ini
[default]
client_token = "akab-1234567890ajze42-5mqabcdeffclu554"
host = "akab-bau2wa3c5iabcdef-x7h1234567890744.luna.akamaiapis.net"
access_token = "akab-abcdefpbzh3yxocw-wns1234567890tp3"
client_secret = "WW91IHdpbGwgbmV2ZXIga25vdyB3aGF0IGl0IGlzCg=="
^D
```

Every call to this CLI is a call to PHP with this script as first argument and the credentials file and section as second and third arguments, all of which could be in a different location from that where you have all your configurations.

I suggest you create an alias and use the alias as the actual command, for which you can use the `alias` command, which makes all paths absolute and returns an `alias` command line ready to be fed into the shell.

```
$ eval $(php papi.php <credentials> <section> alias <alias-name>)
```

This is an example of a particular resulting alias. Notice that all paths are absolute and so the alias can be invoked from any location for the rest of the session.

```
$ alias papi='/Applications/XAMPP/bin/php /Users/me/Development/akamai-cli/papi.php /Users/me/Development/akamai-configs/credentials.ini default'
```

## Usage

### Property Manager API aka PAPI

List all the groups that are visible to your API client.

```
$ papi list-groups
```

List all the contracts that are visible to your API client.

```
$ papi list-contracts
```

List all the products in a contract.

```
$ papi list-products <contract-id>
```

List all the CP codes for a contract and a group.

```
$ papi list-cpcodes <contract-id> <group-id>
```

Create a CP code.

```
$ papi create-cpcode <contract-id> <group-id> <product-id> <cpcode-name>
```

Get the details of a CP code.

```
$ papi get-cpcode <cpcode-id> <contract-id> <group-id>
```

List the edge hostnames for a contract and a group.

```
$ papi list-edge-hostnames <contract-id> <group-id>
```

List the properties for a contract and a group.

```
$ papi list-properties <contract-id> <group-id>
```

Create a property.

```
$ papi create-property <contract-id> <group-id> <product-id> <property-name>
```

Get the details of a property.

```
$ papi get-property <property-id> <contract-id> <group-id>
```

List all the versions of a property.

```
$ papi list-property-versions <property-id> <contract-id> <group-id>
```

Create a new version of a property.

```
$ papi create-property-version <property-id> <contract-id> <group-id> <from-version>
```

Get the details of a version of a property.

```
$ papi get-property-version <property-id> <version-no> <contract-id> <group-id>
```

Get the hostnames of a version of a property.

```
$ papi get-property-version-hostnames <property-id> <version-no> <contract-id> <group-id>
```

Update the hostnames of a version of a property. The hostnames must be fed by stdin in JSON format.

```
$ cat hostnames.json | papi update-property-version-rules <property-id> <version-no> <contract-id> <group-id>
```

Get the rules tree of a version of a property.

```
$ papi get-property-version-rules <property-id> <version-no> <contract-id> <group-id>
```

Update the rules tree of a version of a property. The rules tree must be fed by stdin in JSON format.

```
$ cat rules.json | papi update-property-version-rules <property-id> <version-no> <contract-id> <group-id>
```
