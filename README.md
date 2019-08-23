# Akamai simple CLI

This is a script that calls the API with the parameters you pass it in the command line. The goal is to be able to make calls quickly without too much preparation, or to integrate them in scripts.

Akamai has their own CLI, but it's hard to integrate with other tools.

All the API responses are JSON objects and are pretty-printed to stdout. You can pipe them into (for example) `jq` to extract the individual data.

## Preparation

Get API credentials and save them in a file.

```
$ cat >api.ini
[default]
client_token = "akab-1234567890ajze42-5mqabcdeffclu554"
host = "akab-bau2wa3c5iabcdef-x7h1234567890744.luna.akamaiapis.net"
access_token = "akab-abcdefpbzh3yxocw-wns1234567890tp3"
client_secret = "WW91IHdpbGwgbmV2ZXIga25vdyB3aGF0IGl0IGlzCg=="
^D
```

## Usage

### Property Manager API aka PAPI

List all the groups that are visible to your API client.

```
$ php papi.php api.ini default list-groups
```

List all the contracts that are visible to your API client.

```
$ php papi.php api.ini default list-contracts
```

List all the products in a contract.

```
$ php papi.php api.ini default list-products <contract-id>
```

List all the CP codes for a contract and a group.

```
$ php papi.php api.ini default list-cpcodes <contract-id> <group-id>
```

Create a CP code.

```
$ php papi.php api.ini default create-cpcode <contract-id> <group-id> <product-id> <cpcode-name>
```

Get the details of a CP code.

```
$ php papi.php api.ini default get-cpcode <cpcode-id> <contract-id> <group-id>
```

List the edge hostnames for a contract and a group.

```
$ php papi.php api.ini default list-edge-hostnames <contract-id> <group-id>
```

Create an edge hostname.

```
TO BE IMPLEMENTED
```

List the properties for a contract and a group.

```
$ php papi.php api.ini default list-properties <contract-id> <group-id>
```

Create a property.

```
$ php papi.php api.ini default create-property <contract-id> <group-id> <product-id> <property-name>
```

Get the details of a property.

```
$ php papi.php api.ini default get-property <property-id> <contract-id> <group-id>
```

List all the versions of a property.

```
$ php papi.php api.ini default list-property-versions <property-id> <contract-id> <group-id>
```

Create a new version of a property.

```
$ php papi.php api.ini default create-property-version <property-id> <contract-id> <group-id> <from-version>
```

Get the details of a version of a property.

```
$ php papi.php api.ini default get-property-version <property-id> <version-no> <contract-id> <group-id>
```

Get the hostnames of a version of a property.

```
$ php papi.php api.ini default get-property-version-hostnames <property-id> <version-no> <contract-id> <group-id>
```

Update the hostnames of a version of a property.

```
TO BE IMPLEMENTED
```

Get the rules tree of a version of a property.

```
$ php papi.php api.ini default get-property-version-rules <property-id> <version-no> <contract-id> <group-id>
```

Update the rules tree of a version of a property.

```
TO BE IMPLEMENTED
```
