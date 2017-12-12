<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class {{nombre_modelo}} extends CI_Model {
        private $nombreTabla;
        private $columnas;

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
            // Your own constructor code
            $this->nombreTabla= "{{tabla}}";
            $this->obteneNombreColumnas();
        }

        /**
        * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-09-28 12:58:38
        * @param  null
        * @return  void
        * Descripción: Obtiene el nombre todas las columnas de la tabla {{tabla}}
        */
        private function obteneNombreColumnas(){
            $registros = $this->db->query("SHOW COLUMNS FROM ".$this->nombreTabla)->result_array();
            foreach($registros as $registro){
                $columnas[] = $registro['Field'];
            }
            $this->columnas = $columnas;
        }

        /**
        * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-09-28 12:59:59
        * @param $array columnas de la tabla {{tabla}} a editar
        * @return  $array el array con las columnas que si pertenecen la tabla {{tabla}}
        * Descripción: deja en el array solo las columas que dejan a la tabla {{tabla}}
        */
        private function filtrarArray( $array ){
            foreach($array as $llave => $valor){
                if( in_array( $llave  , $this->columnas ) == false ){
                    unset( $array[ $llave ] );
                }
            }

            return $array;
        }

        /** Carlos Aguirre 2017-07-30 18:21:49  
        *   @param $id un int con el id del registro 
        *   @return array con la informacion del registro con el id brindado
        *   Descripcion: Obtiene el registro de la tabla con el id brindado
        **/
        public function obtenerRegistroPorId( $id = "" ){
            return $this->db->select('*')
                            ->from( $this->nombreTabla )
                            ->where( [
                                $this->nombreTabla.".id" => $id
                            ] )
                            ->get()
                            ->first_row( 'array' );
        }

        /** Carlos Aguirre 2017-07-30 18:24:03
        *   @param $data un array con los datos a actualizar
        *   @return void
        *   Descripcion: Actualiza un registro de la tabla indicada
        **/
        public function actualizar( $data ){
            $data = $this->filtrarArray( $data );
            $this->db->set( $data )
                     ->where( [
                         $this->nombreTabla.".id" => $data['id']
                     ] )
                     ->update( $this->nombreTabla );
        }

        /** Carlos Aguirre 2017-07-30 18:24:55
        *   @param $data un array asociativo con los datos a insertar
        *   @return int con el  id del registro creado
        *   Descripcion: Inserta un registro en la tabla
        **/
        public function crear( $data ){
            $data = $this->filtrarArray( $data );
            $this->db->insert( $this->nombreTabla, $data );
            return $this->db->insert_id();
        }

        /** Carlos Aguirre 2017-07-30 18:30:12
        *   @param $condicion un array con las condiciones de borrado que se pondran en el where
        *   @return void
        *   Descripcion: Borra registros de la base de datos dada una condicion
        **/
        public function eliminar( $condicion ){
            $this->db->where( $condicion );
            $this->db->delete( $this->nombreTabla );
        }

        /** Carlos Aguirre 2017-07-30 18:33:22
        *   @param void
        *   @return array con los registros de la tabla
        *   Descripcion: Obtiene un array con todos los registros de la tabla
        **/
        public function obtenerTodo(){
            return $this->db->get( $this->nombreTabla )->result_array();
        }

        /** Carlos Aguirre 2017-07-30 18:42:34
        *   @param $criterio un String con el criterio de busqueda
        *   @param $pagina un int con la pagina de resultados a mostrar
        *   @param $cantidad un int con la cantidad de registros a mostrar por pagina
        *   @param $contar un booleano si es true la funcion devuelve la cantidad de resultados                
        *   @return array con los registros de la bd o con el numero de resultados
        *   Descripcion: Obtiene una lista de registros paginada y filtrada por un criterio
        **/
        public function obtenerListaRegistros( $criterio = "", $pagina = 0, $cantidad= 20, $contar = false ){
            $this->db->select('*')
                     ->from( $this->nombreTabla );
            if( $contar ){
                $this->db->stop_cache();
                return $this->db->count_all_results();
            }

            return $this->db->limit( $cantidad, ( $pagina * $cantidad ) )->get()->result_array();
        }

}