<?php

require 'akamai-open-edgegrid-auth-1.0.1.phar';

class Command {
  protected $method, $url, $urlArgs, $body = '', $bodyArgs = [], $stdin, $headers = [];

  public function __construct($method, $url, $urlArgs) {
    $this->method = strtoupper($method);
    $this->url = $url;
    $this->urlArgs = $urlArgs;
  }

  public function body($body, $bodyArgs = []) {
    $this->body = $body;
    $this->bodyArgs = $bodyArgs;
    return $this;
  }

  public function headers($headers) {
    $this->headers = $headers;
    return $this;
  }

  public function contentType($type) {
    $this->headers[] = sprintf('Content-Type: %s', $type);
    return $this;
  }

  public function accept($type) {
    $this->headers[] = sprintf('Accept: %s', $type);
    return $this;
  }

  public function stdin($stdin) {
    $this->stdin = $stdin;
    return $this;
  }

  public function export($prefix) {
    $result = [];
    $result['method'] = $this->method;
    $result['url'] = sprintf('%s/%s', $prefix, $this->url);
    $result['urlArgs'] = $this->urlArgs;
    $result['body'] = $this->body;
    $result['bodyArgs'] = $this->bodyArgs;
    $result['headers'] = $this->headers;
    if (isset($this->stdin)) $result['stdin'] = $this->stdin;
    return $result;
  }
}

class CommandLineInterface {

  protected $file, $prefix = '', $commands = [];

  public static function create($file, $prefix, $commands = []) {
    return new static($file, $prefix, $commands);
  }

  protected function __construct($file, $prefix, $commands) {
    $this->file = $file;
    $this->prefix = $prefix;
    foreach ($commands as $name => $command) $this->commands[$name] = $command->export($prefix);
  }

  public function run($argv) {
    array_shift($argv); // myself
    $credentials = array_shift($argv);
    $section = array_shift($argv);
    $cname = array_shift($argv);
    if ($cname === 'usage') {
      $this->usage();
    } else if ($cname === 'alias') {
      $name = array_shift($argv);
      printf("alias %s='%s %s %s %s'\n", $name, $_SERVER['_'], $this->file, realpath($credentials), $section);
    } else {
      $this->execute($credentials, $section, $cname, $argv);
    }
  }

  public function execute($credentials, $section, $cname, $argv) {
    if ($cdef = $this->commands[$cname]) {

      $method = $cdef['method'];

      $urlArgs = [];
      $urlArgs[] = $cdef['url'];
      for ($i = 0; $i < count($cdef['urlArgs']); $i++) $urlArgs[] = array_shift($argv);
      $path = call_user_func_array('sprintf', $urlArgs);

      if (in_array($method, ['POST', 'PUT'])) {
        if (empty($cdef['body'])) {
          $body = file_get_contents('php://stdin');
        } else if (is_callable($cdef['body'])) {
          $body = json_encode(call_user_func_array($cdef['body'], $argv));
        } else {
          $bodyArgs = [];
          $bodyArgs[] = $cdef['body'];
          for ($i = 0; $i < count($cdef['bodyArgs']); $i++) $bodyArgs[] = array_shift($argv);
          $body = call_user_func_array('sprintf', $bodyArgs);
        }
      } else {
        $body = '';
      }

      $headers = [];
      if (!empty($cdef['headers'])) {
        $headers = array_merge($headers, $cdef['headers']);
      }

      $auth = \Akamai\Open\EdgeGrid\Authentication::createFromEdgeRcFile($section, $credentials);
      $auth->setHttpMethod($method);
      $auth->setPath($path);
      if (!empty($body)) {
        $auth->setBody($body);
      }
      if (!empty($cdef['headers'])) {
        $auth->setHeaders($headers);
      }

      $url = 'https://' . $auth->getHost() . $auth->getPath() . '?' . $auth->getQuery();

      $headers[] = 'PAPI-Use-Prefixes: true';
      $headers[] = sprintf('Authorization: %s', $auth->createAuthHeader());
      if (!empty($body)) {
        $headers[] = 'Content-Type: application/json';
        $headers[] = sprintf('Content-Length: %d', strlen($body));
      }

      $http = [
        'ignore_errors' => true,
        'method' => $method,
        'header' => $headers,
      ];
      if (!empty($body)) {
        $http['content'] = $body;
      }

      $response = file_get_contents($url, null, stream_context_create(['http' => $http]));
      $decoded = json_decode($response);
      $encoded = json_encode($decoded, JSON_PRETTY_PRINT);
      printf("%s\n", $encoded);

    } else {
      throw new Exception('Unknown command');
    }
  }

  public function usage() {

    function tput($string) {
      $result = '';
      while (strlen($string)) {
        $result .= chr(hexdec(substr($string, 0, 2)));
        $string = substr($string, 2);
      }
      return $result;
    }

    foreach ($this->commands as $name => $def) {
      $line = '';
      if ($def['stdin']) {
        $line .= sprintf("cat %s | ", $def['stdin']);
      }
      $line .= tput('1b5b316d') . $name . tput('1b28421b5b6d');
      foreach ($def['urlArgs'] as $arg) $line .= sprintf(" <%s>", $arg);
      foreach ($def['bodyArgs'] as $arg) $line .= sprintf(" <%s>", $arg);
      printf("  %s\n", $line);
    }
  }

}

function get($url, $urlArgs = []) {
  return new Command('get', $url, $urlArgs);
}

function post($url, $urlArgs = [], $stdin = null) {
  $post = new Command('post', $url, $urlArgs);
  if (isset($stdin)) $post->stdin($stdin);
  return $post;
}

function put($url, $urlArgs = [], $stdin = null) {
  $put = new Command('put', $url, $urlArgs);
  if (isset($stdin)) $put->stdin($stdin);
  return $put;
}

function delete($url, $urlArgs = []) {
  return new Command('delete', $url, $urlArgs);
}

function cli($file, $prefix, $commands) {
  global $argv;
  CommandLineInterface::create($file, $prefix, $commands)->run($argv);
}
