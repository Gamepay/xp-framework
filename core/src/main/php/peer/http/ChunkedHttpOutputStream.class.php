<?php
uses('peer.http.HttpOutputStream');

class ChunkedHttpOutputStream extends HttpOutputStream {
    const BUFFER_SIZE = 4096;
    public $socket;
    private $buffer= '', $closed= false;
    /** @param peer.Socket $socket */
    public function __construct($socket) {
        $this->socket= $socket;
    }
    /**
     * Write given bytes
     *
     * @param  string $bytes
     * @return void
     */
    public function write($bytes) {
        $this->buffer.= $bytes;
        if (strlen($this->buffer) > self::BUFFER_SIZE) {
            $this->socket->write(dechex(strlen($this->buffer))."\r\n".$this->buffer."\r\n");
            $this->buffer= '';
        }
    }
    /** @return void */
    public function close() {
        if ($this->closed) return;
        if ('' === $this->buffer) {
            $this->socket->write("0\r\n\r\n");
            $this->closed= true;
        } else {
            $this->socket->write(dechex(strlen($this->buffer))."\r\n".$this->buffer."\r\n0\r\n\r\n");
            $this->closed= true;
        }
    }
}
