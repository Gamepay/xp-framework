<?php

uses('io.streams.OutputStream');

/**
 * HTTP transfer (client -> server)
 *
 * @see   xp://peer.http.HttpConnection::open
 */
abstract class HttpOutputStream implements OutputStream {
    /** @return void */
    public function flush() {
        // NOOP, overwrite in subclasses if necessary
    }

    /** @return void */
    public function close() {
        // NOOP, overwrite in subclasses if necessary
    }
}
