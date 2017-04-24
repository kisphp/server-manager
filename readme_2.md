Kisphp Web Server Manager
=========================

[![Build Status](https://travis-ci.org/kisphp/server-manager.svg?branch=master)](https://travis-ci.org/kisphp/master)
[![codecov](https://codecov.io/gh/kisphp/server-manager/branch/master/graph/badge.svg)](https://codecov.io/gh/kisphp/server-manager)

The purpose of this repository is to allow an easy management of database and web server.

> Use this only in development servers! Do not use it in production servers !!!

## Commands:

| Command | Shortcut | Description |
| --- | --- |
| `./kisphp db:list` | `./kisphp d:l` | list databases and permissions |
| `./kisphp db:create database_name [database_user] [database_pass]` | `./kisphp d:c` | create database and user |
| `./kisphp db:drop database_name` | `./kisphp d:d` | drop database and user |
| `./kisphp nginx:activate dev.local` | `./kisphp n:a dev.local` | enable nginx dev.local website |
| `./kisphp nginx:deactivate dev.local` | `./kisphp n:d dev.local` | disable nginx dev.local website |
| `./kisphp` | `./kisphp` | list available commands |
