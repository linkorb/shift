Shift: Framework for Reusable Stateless Functions
=================================================

Inspired by the trends in Serverless / FaaS / Cloud Functions.

## Features:

* Provides a framework to build reusable stateless functions.
* A language agnostic (json) format to define `inputs`, `outputs` and `configs` that your functions need.
* A service.json format to list the Shift functions you'd like to expose.
* Uses JSON Schema to validate all input, output and configs.
* Invokers for your Shift functions, so you can easily call/host them locally or remotely.
* Supports functions implemented in PHP or any other language, including executing external commands.
* An HTTP end-point server to serve your functions.
* A Console tool to help build, test and debug your Shift functions.

## Examples:

The `example/` directory contains an example service with 2 functions, one implemented in PHP, and one generically executing an external CLI tool.

To test it out:

    cd example/
    ../bin/shift invoke:local hello-php -i greeting=Howdy -c color=silver -u joe

This will call the `hello-php` function, passing one input (greeting), a config (color) and a context username.

## Status

Shift is currently in an experimental phase, and some of the features are under construction.

## License

MIT. Please refer to the [license file](LICENSE) for details.

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
