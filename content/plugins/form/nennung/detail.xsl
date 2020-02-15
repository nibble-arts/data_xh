<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html"/>

	<xsl:param name="uri"/>
	<xsl:param name="form"/>
	<xsl:param name="prefix"/>
	<xsl:param name="return"/>


	<!-- match all nodes -->
	<xsl:template match="/">

		<script type="text/javascript" src="plugins/form/script/form.js"/>

		<form method="post" class="form_form">
			<xsl:attribute name="action">
				<xsl:value-of select="$return"/>
			</xsl:attribute>

			<xsl:apply-templates select="//data/*"/>
		</form>

	</xsl:template>



	<xsl:template match="data/*">

		<xsl:param name="pos" select="position()"/>
		<xsl:variable name="id" select="id"/>
		<xsl:variable name="data" select="."/>

		<div class="form_detail_block">

			<div class="form_detail_line">
				<div class="form_detail_label">ID</div>
				<div class="form_detail_value"><xsl:value-of select="$data/id"/></div>
			</div>

			<xsl:for-each select="//fields/*">

				<div class="form_detail_line">

					<xsl:variable name="n" select="name(.)"/>
					<xsl:variable name="name">
						<xsl:value-of select="$prefix"/>
						<xsl:text>_</xsl:text>
						<xsl:value-of select="$id"/>
						<xsl:text>_</xsl:text>
						<xsl:value-of select="$n"/>
					</xsl:variable>

					<div class="form_detail_label">
						<xsl:value-of select="$n"/>
					</div>

					<div class="form_detail_value">

						<xsl:call-template name="content">
							<xsl:with-param name="value">
								<xsl:value-of select="$data/*[name() = $n]"/>
							</xsl:with-param>

							<xsl:with-param name="name" select="$name"/>
							<xsl:with-param name="type" select="type"/>
							<xsl:with-param name="mandatory" select="mandatory"/>
							<xsl:with-param name="check" select="check"/>
							<xsl:with-param name="source" select="source"/>

						</xsl:call-template>

					</div>
				</div>

				<div style="clear:both;"/>

			</xsl:for-each>

			<div class="form_detail_line">
				<input type="submit" class="form_submit" name="form_button" value="speichern"/>
				<input type="submit" class="form_submit" name="form_button" value="abbrechen"/>

			</div>

		</div>

		<input type="hidden" name="form_action" value="form_update"/>

		<input type="hidden" value="{$id}">
			<xsl:attribute name="name">
				<xsl:value-of select="$prefix"/>
				<xsl:text>_</xsl:text>
				<xsl:value-of select="$id"/>
				<xsl:text>_id</xsl:text>
			</xsl:attribute>
		</input>


	</xsl:template>


	<xsl:template name="content">

		<xsl:param name="name"/>
		<xsl:param name="type"/>
		<xsl:param name="mandatory"/>
		<xsl:param name="check"/>
		<xsl:param name="value"/>
		<xsl:param name="source"/>

		<xsl:choose>

			<xsl:when test="$type = 'input'">

				<input type="input" name="">
					<xsl:call-template name="attributes">
						<xsl:with-param name="name" select="$name"/>
						<xsl:with-param name="type" select="$type"/>
						<xsl:with-param name="mandatory" select="$mandatory"/>
						<xsl:with-param name="check" select="$check"/>
						<xsl:with-param name="value" select="$value"/>
					</xsl:call-template>
				</input>

			</xsl:when>

			<xsl:when test="$type = 'select'">

				<select name="">
					<xsl:call-template name="attributes">
						<xsl:with-param name="name" select="$name"/>
						<xsl:with-param name="type" select="$type"/>
						<xsl:with-param name="mandatory" select="$mandatory"/>
						<xsl:with-param name="value" select="$value"/>
						<xsl:with-param name="source" select="$source"/>
					</xsl:call-template>
				</select>

			</xsl:when>

			<xsl:when test="$type = 'checkbox'">

				<checkbox name="">
					<xsl:call-template name="attributes">
						<xsl:with-param name="name" select="$name"/>
						<xsl:with-param name="type" select="$type"/>
						<xsl:with-param name="mandatory" select="$mandatory"/>
						<xsl:with-param name="value" select="$value"/>
						<xsl:with-param name="source" select="$source"/>
					</xsl:call-template>
				</checkbox>

			</xsl:when>

			<xsl:when test="$type = 'radio'">
			</xsl:when>

			<xsl:when test="$type = 'textarea'">
			</xsl:when>

		</xsl:choose>

	</xsl:template>


	<xsl:template name="attributes">

		<xsl:param name="name"/>
		<xsl:param name="type"/>
		<xsl:param name="mandatory"/>
		<xsl:param name="check"/>
		<xsl:param name="value"/>
		<xsl:param name="source"/>

		<xsl:attribute name="name">
			<xsl:value-of select="$name"/>
		</xsl:attribute>

		<xsl:if test="$type != ''">
			<xsl:attribute name="type">
				<xsl:value-of select="$type"/>
			</xsl:attribute>
		</xsl:if>

		<xsl:if test="$mandatory != ''">
			<xsl:attribute name="mandatory">
				<xsl:value-of select="$mandatory"/>
			</xsl:attribute>
		</xsl:if>

		<xsl:if test="$check != ''">
			<xsl:attribute name="check">
				<xsl:value-of select="$check"/>
			</xsl:attribute>
		</xsl:if>

		<xsl:if test="$source != ''">
			<xsl:attribute name="source">
				<xsl:value-of select="$source"/>
			</xsl:attribute>
		</xsl:if>

		<xsl:if test="$value != ''">
			<xsl:attribute name="value">
				<xsl:value-of select="$value"/>
			</xsl:attribute>
		</xsl:if>

	</xsl:template>

</xsl:stylesheet>