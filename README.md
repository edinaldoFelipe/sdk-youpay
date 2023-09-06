# PHP SDK to API Youpay

## Endpoints / Sandbox

[Documentation](https://youpay.readme.io/reference/introdução)

SDK to implement Youpay API

### Features

- Create Charges
- Purchase by Credit Card
- Purchase by Pix
- Save Credit Card

### Examples

```sh
use Smart\SdkYoupay\Charge;

$response = (new Charge()) -> find('F59DBD3C-F960-4B1F-B59D-67B9EDF1620F');
```

> In folder **examples**, has all features examples.

## Install

### Add Project Folder

Paste the project folder into vendor folder

```sh
|--packages
|----sdkYoupay
```

### Add configs to file composer.json

Open file **composer.json** and add the script

```sh
"repositories": [
  {
    "type": "path",
    "url": "./packages/sdkYoupay"
  }
],
"require": {
  "smart/sdkYoupay": "1.0.0"
}
```

### Update composer

Run in terminal project

```sh
composer update
```

### Define Environments

In .env file

```sh
YOUPAY_ID_ESTABLISHMENT=
YOUPAY_CLIENT_ID=
YOUPAY_SECRET_KEY=
YOUPAY_ACCESS_TOKEN=
YOUPAY_EXPIRE_TIME=
YOUPAY_URL=https://homolog.youpay.digital/
```

## Tests

Run

```sh
composer tests
```
