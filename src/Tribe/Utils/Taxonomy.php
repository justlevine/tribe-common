<?php

namespace Tribe\Utils;

/**
 * Class Taxonomy.
 *
 * @since   TBD
 *
 * @package Tribe\Utils
 */
class Taxonomy {
	/**
	 * Match any operand.
	 *
	 * @since TBD
	 * @since TBD
	 *
	 * @var string
	 */
	const OPERAND_OR = 'OR';

	/**
	 * Match all operand.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	const OPERAND_AND = 'AND';

	/**
	 * Default operand for taxonomy filters.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	const DEFAULT_OPERAND = self::OPERAND_OR;

	/**
	 * Translates a given argument to repository arguments.
	 *
	 * @since TBD
	 *
	 * @param string       $taxonomy Which taxonomy we are using to setup.
	 * @param string|array $terms    Which terms we are going to use here.
	 * @param string       $operand  Which is the operand we should use.
	 *
	 * @return array A fully qualified `tax_query` array, merge using `array_merge_recursively`.
	 */
	public static function translate_to_repo( $taxonomy, $terms, $operand = self::OPERAND_OR ) {
		$tax_query = [];
		// Prevent empty values from even trying.
		if ( empty( $taxonomy ) ) {
			return $tax_query;
		}

		// Prevent empty values from even trying.
		if ( empty( $terms ) ) {
			return $tax_query;
		}

		$repo = tribe_events();

		$operation = static::OPERAND_AND === $operand ? 'term_and' : 'term_in';

		$repo->by( $operation, $taxonomy, $terms );

		// This will only build the query not execute it.
		$built_query = $repo->build_query();

		if ( ! empty( $built_query->query_vars['tax_query'] ) ) {
			$tax_query = $built_query->query_vars['tax_query'];
		}

		return $tax_query;
	}

	/**
	 * Transform all Term IDs and Slugs into IDs of existing terms in a given taxonomy.
	 *
	 * @since TBD
	 *
	 * @param string|int|array $terms Terms to be cleaned up.
	 * @param string           $taxonomy Which taxonomy we are querying for.
	 *
	 * @return array List of IDs of terms.
	 */
	public static function sanitize_shortcode_terms_to_id( $terms, $taxonomy ) {
		if ( empty( $terms ) ) {
			return $terms;
		}

		$terms = array_map( static function ( $param ) use ( $taxonomy ) {
			$param   = preg_replace( '/^#/', '', $param );
			$term_by = is_numeric( $param ) ? 'ID' : 'slug';
			$term    = get_term_by( $term_by, $param, $taxonomy );

			if ( ! $term instanceof \WP_Term ) {
				return false;
			}

			return $term->term_id;
		}, (array) $terms );

		$terms = array_filter( $terms );
		$terms = array_unique( $terms );

		return $terms;
	}
}